---
layout: page
title: Search
permalink: /search/
search_exclude: true
---
<style>
  #search-input { width: 100%; padding: .5em; font-size: 1em; box-sizing: border-box; }
  #search-results { list-style: none; margin: 1em 0 0; padding: 0; }
  .search-result { margin-bottom: 1.25em; }
  .search-result-title { font-weight: 600; }
  .search-result-snippet { margin: .25em 0 0; font-size: .9em; color: #555; }
  .search-result-snippet mark { background: #fff3a3; padding: 0 1px; }
  .search-message { color: #777; }
</style>

<input type="search" id="search-input" placeholder="Search the guide…"
       data-index="{{ '/search.json' | relative_url }}" autofocus>
<ul id="search-results"></ul>

<script src="{{ '/assets/js/lunr.min.js' | relative_url }}"></script>
<script src="{{ '/assets/js/search.js' | relative_url }}"></script>