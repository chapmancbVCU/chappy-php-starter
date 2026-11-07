<h1 style="font-size: 50px; text-align: center;">Supported Traits</h1>

## Table of contents
1. [Overview](#overview)
2. [HasTimestamps](#has-timestamps)
3. [PasswordPolicy](#password-policy)

<br>

## 1. Overview <a id="overview"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
This framework currently supports 4 traits intended for developer use:
- HasTimestamps
- JsonResponse - More details [here](api_util_and_json_response_trait#json-response-trait).
- Notifiable - More details [here](notifications#making-model-notifiable)
- PasswordPolicy

<br>

## 2. HasTimestamps <a id="has-timestamps"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Supports timestamp related features for your `$created_at` and `$updated_at` database fields.  The `timeStamps` function is used to manage these fields.


<br>

## 3. PasswordPolicy <a id="password-policy"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Contains a collection of functions for enforcing your project's password policy.  The base user model, `Core\Models\User`, uses this trait and applies each enabled rule when a user registers or changes their password.
- `isMaxLength` - True if the maximum length rule is enabled
- `isMinLength` - True if the minimum length rule is enabled
- `lowerChar` - True if a lowercase character is required
- `maxLength` - Maximum allowed length (`int`)
- `minLength` - Minimum allowed length (`int`)
- `numericChar` - True if a number is required
- `specialChar` - True if a special character is required
- `upperChar` - True if an uppercase character is required

Values are read from `config/password.php`, which reads the `PW_*` variables in your `.env` file.  Applications without `config/password.php` fall back to reading `.env` directly.  If neither is set, every rule is off and the lengths default to 12 and 25.  See [Password Policy](config_env) for the variables.