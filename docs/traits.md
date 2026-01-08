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
Contains a collection of functions for enforcing your project's password policy:
- `isMaxLength` - True if this policy is enabled
- `isMinLength` - True if this policy is enabled
- `lowerChar` - True if this policy is enabled
- `maxLength` - Maximum allowed length
- `minLength` - Minimum allowed length
- `numericChar` - True if this policy is enabled
- `specialChar` - True if this policy is enabled
- `upperChar` - True if this policy is enabled

<br>

## 3. PasswordPolicy <a id="password-policy"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>