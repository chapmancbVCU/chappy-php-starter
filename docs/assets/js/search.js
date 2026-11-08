/**
 * Client-side search for the Chappy.php user guide.
 *
 * Loads the section-level index from search.json (built by Jekyll), builds a
 * Lunr index in the browser, and renders results linking to each section.
 */
(function () {
  'use strict';

  var input = document.getElementById('search-input');
  var list = document.getElementById('search-results');
  if (!input || !list || typeof lunr === 'undefined') return;

  var MAX_RESULTS = 25;
  var SNIPPET_LENGTH = 180;

  // Separators inside code identifiers: Arr::get, $this->request, config.app,
  // App\Models\Users, has_validators, add()
  var CODE_SEPARATORS = /::|->|[.:\\\/$()_\[\]]+/;

  /** Splits "arr::get" into ["arr", "get"]; returns [] when there is nothing to split. */
  function codeParts(str) {
    return str.split(CODE_SEPARATORS).filter(function (part) {
      return part.length > 1 && part !== str;
    });
  }

  /**
   * Lunr pipeline step: keep the full token and also emit its parts, so
   * "Arr::get" is findable by "arr::get", "arr", or "get".
   */
  function codeSplitter(token) {
    var parts = codeParts(token.toString());
    if (!parts.length) return token;
    return [token].concat(parts.map(function (part) {
      return token.clone(function () { return part; });
    }));
  }
  lunr.Pipeline.registerFunction(codeSplitter, 'codeSplitter');

  /** Decodes HTML entities (&gt;, &amp;, ...) left behind by strip_html. */
  var decoder = document.createElement('textarea');
  function decode(str) {
    decoder.innerHTML = str;
    return decoder.value;
  }

  /** Turns the search box text into the terms we query for, including code parts. */
  function queryTerms(q) {
    var terms = [];
    lunr.tokenizer(q).forEach(function (token) {
      var term = token.toString().replace(/^\W+|\W+$/g, '');
      if (!term) return;
      [term].concat(codeParts(term)).forEach(function (t) {
        if (terms.indexOf(t) === -1) terms.push(t);
      });
    });
    return terms;
  }

  /** Builds a snippet centred on the first matching term. */
  function snippet(text, terms) {
    var lower = text.toLowerCase();
    var pos = -1;
    terms.forEach(function (t) {
      var i = lower.indexOf(t);
      if (i !== -1 && (pos === -1 || i < pos)) pos = i;
    });
    var start = Math.max(0, pos - 60);
    var out = text.substr(start, SNIPPET_LENGTH);
    return (start > 0 ? '…' : '') + out + (start + SNIPPET_LENGTH < text.length ? '…' : '');
  }

  /** Appends text to el, wrapping occurrences of any term in <mark>. Uses text nodes only. */
  function appendHighlighted(el, text, terms) {
    var escaped = terms
      .filter(function (t) { return t.length > 1; })
      .map(function (t) { return t.replace(/[.*+?^${}()|[\]\\]/g, '\\$&'); });
    if (!escaped.length) {
      el.appendChild(document.createTextNode(text));
      return;
    }
    var re = new RegExp('(' + escaped.join('|') + ')', 'gi');
    text.split(re).forEach(function (piece, i) {
      if (i % 2 === 1) {
        var mark = document.createElement('mark');
        mark.textContent = piece;
        el.appendChild(mark);
      } else if (piece) {
        el.appendChild(document.createTextNode(piece));
      }
    });
  }

  function setMessage(text) {
    list.innerHTML = '';
    var li = document.createElement('li');
    li.className = 'search-message';
    li.textContent = text;
    list.appendChild(li);
  }

  var idx = null;
  var docs = [];

  function run(q) {
    q = q.trim();

    // Keep ?q= in the address bar so a search can be bookmarked or shared
    var url = new URL(window.location.href);
    if (q) { url.searchParams.set('q', q); } else { url.searchParams.delete('q'); }
    window.history.replaceState(null, '', url);

    list.innerHTML = '';
    if (!idx || q.length < 2) return;

    var terms = queryTerms(q.toLowerCase());
    if (!terms.length) return;

    var hits = idx.query(function (builder) {
      terms.forEach(function (t) {
        builder.term(t, { boost: 10 });                    // exact (stemmed) match
        builder.term(t, {                                  // prefix match: "contr" -> "controller"
          wildcard: lunr.Query.wildcard.TRAILING,
          usePipeline: false
        });
      });
    });

    if (!hits.length) {
      setMessage('No results for "' + q + '".');
      return;
    }

    hits.slice(0, MAX_RESULTS).forEach(function (hit) {
      var d = docs[hit.ref];
      var li = document.createElement('li');
      li.className = 'search-result';

      var a = document.createElement('a');
      a.href = d.url;
      a.className = 'search-result-title';
      a.textContent = d.title ? d.page + ' › ' + d.title : d.page;

      var p = document.createElement('p');
      p.className = 'search-result-snippet';
      appendHighlighted(p, snippet(d.content, terms), terms);

      li.appendChild(a);
      li.appendChild(p);
      list.appendChild(li);
    });
  }

  setMessage('Loading search index…');

  fetch(input.dataset.index)
    .then(function (r) {
      if (!r.ok) throw new Error(r.status);
      return r.json();
    })
    .then(function (data) {
      docs = data.map(function (d, i) {
        return {
          id: String(i),
          page: decode(d.page),
          title: decode(d.title),
          url: d.url,
          content: decode(d.content)
        };
      });

      idx = lunr(function () {
        // Default pipeline is trimmer + stopWordFilter + stemmer. Split code
        // identifiers first, and drop the stop-word filter: it removes words
        // like "get", "has", and "only", which are method names in this guide.
        this.pipeline.reset();
        this.pipeline.add(codeSplitter, lunr.trimmer, lunr.stemmer);

        this.ref('id');
        this.field('page', { boost: 5 });
        this.field('title', { boost: 10 });
        this.field('content');

        docs.forEach(function (d) { this.add(d); }, this);
      });

      list.innerHTML = '';
      var q = new URLSearchParams(window.location.search).get('q');
      if (q) { input.value = q; }
      run(input.value);
    })
    .catch(function () {
      setMessage('Search is unavailable right now. Please try again later.');
    });

  var timer;
  input.addEventListener('input', function () {
    clearTimeout(timer);
    timer = setTimeout(function () { run(input.value); }, 150);
  });
})();