---
layout: page
title: Search
permalink: /search/
search_exclude: true
---
<input type="search" id="search-input" placeholder="Search the guide…"
       data-index="{{ '/search.json' | relative_url }}" autofocus
       style="width:100%;padding:.5em;font-size:1em;">
<ul id="search-results"></ul>

<script src="https://unpkg.com/lunr@2.3.9/lunr.min.js"></script>
<script src="{{ '/assets/js/search.js' | relative_url }}"></script>