---
layout: post
title:  "Version 4.0.2 Release Notes!"
date:   2026-11-15 12:00:00 -0500
categories: hotfix
---
---

### Added
- `@core-css` and `@core-js` aliases to the jsconfig.json file.  Result of this update is that these aliases will be detected by intellisense or similar tools.

### Fixed
- Resolved issue within the composer.json file with the starter project and core stubs where the require-dev object was nested within the scripts object.