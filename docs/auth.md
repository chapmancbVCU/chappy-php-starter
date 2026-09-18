<h1 style="font-size: 50px; text-align: center;">Authentication</h1>

## Table of contents
1. [Overview](#overview)





<br>

## 1. Overview <a id="overview"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Chappy.php provides a session-based authentication system for logging users in and out, checking who is currently authenticated, and optionally keeping users logged in across browser sessions via a "remember me" token. The system is built around a small set of pieces that work together: a static `Auth` entry point for everyday use, a `SessionGuard` that owns the current authenticated user for a request, a `UserProvider` that retrieves users from storage, and a `Hasher` for password verification. Most applications interact only with the `Auth` entry point and the login flow; the underlying pieces are pluggable for applications with custom authentication needs.

By default, authentication is backed by the `Users` model and verifies passwords with bcrypt. Remember-me tokens are stored as hashes in the `user_sessions` table, keyed per device, so a leaked session table never exposes usable tokens.