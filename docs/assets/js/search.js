(function () {
  const input = document.getElementById('search-input');
  const list = document.getElementById('search-results');
  let idx, docs = {};

  fetch(input.dataset.index)
    .then(r => r.json())
    .then(data => {
      data.forEach(d => (docs[d.url] = d));
      idx = lunr(function () {
        this.ref('url');
        this.field('title', { boost: 10 });
        this.field('content');
        data.forEach(d => this.add(d));
      });
      const q = new URLSearchParams(location.search).get('q');
      if (q) { input.value = q; run(q); }
    });

  function snippet(text, term) {
    const i = text.toLowerCase().indexOf(term.toLowerCase());
    const start = Math.max(0, i - 60);
    return (start > 0 ? '…' : '') + text.substr(i < 0 ? 0 : start, 160) + '…';
  }

  function run(q) {
    list.innerHTML = '';
    q = q.trim();
    if (!idx || q.length < 2) return;

    const terms = lunr.tokenizer(q).map(t => t.toString());
    const hits = idx.query(b => terms.forEach(t => {
      b.term(t, { boost: 10 });                       // exact/stemmed match
      b.term(t, { wildcard: lunr.Query.wildcard.TRAILING, usePipeline: false }); // prefix match
    }));

    if (!hits.length) {
      list.innerHTML = '<li>No results.</li>';
      return;
    }
    hits.slice(0, 20).forEach(h => {
      const d = docs[h.ref];
      const li = document.createElement('li');
      const a = document.createElement('a');
      a.href = d.url;
      a.textContent = d.title;
      const p = document.createElement('p');
      p.textContent = snippet(d.content, terms[0]);
      li.append(a, p);
      list.appendChild(li);
    });
  }

  input.addEventListener('input', e => run(e.target.value));
})();