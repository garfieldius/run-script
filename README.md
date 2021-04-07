# Run Script

This is a TYPO3 extension that allows an editor to run configured server-side scripts via a backend interface. It will
add a dropdown menu to the toolbar of the backend to select the script to run.

## Installation

Use composer to add the extension as a dependency: `composer require grossberger-georg/run-script`.

## Configuration

> **Important**: The webserver must have nohup and bash installed and available to the user running in the PHP process
> in its $PATH variable.

### Creating a script

The executing script will change its working directory to the root path of the project and export all available
environment variables before execution. Example: in a composer-mode installation with default options, the script
`./vendor/bin/typo3 scheduler:run` would start the scheduler.

The script is not escaped, quoted or in any other way changed. It is executed as it is given.

Any valid bash command is a valid script, so this is allowed too:
`if [[ ! -f /tmp/hello ]]; then echo Hello > /tmp/hello; sleep 200; rm /tmp/hello; fi`

If the scripts points to a file, make sure it has the executable bit set.

If it is a file and has an EXT: prefix it will be resolved to an absolute path. An EXT: prefix somewhere in between will
not be resolved. The script `EXT:shop/Resources/Private/Scripts/import.sh` will work, but
`if [[ ! -f /tmp/importing ]]; then EXT:shop/Resources/Private/Scripts/import.sh; fi;` will not.

Only one instance of a script can run at a time. EXT:run_script will create a lock and inform a user if there is already
a process running that script.

### Setup scripts to run

A script is two parts: a key as well as an array with at least two entries: a name and the bash command to execute. They
are added to the global TYPO3_CONF_VARS:

```php
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['run_script']['<KEY>'] = [
    'label'  => '<LABEL>',
    'script' => '<SCRIPT>',
];
```

Use an extensions ext_localconf.php or the AdditionalConfiguration.php file to add scripts. A simple demo is included
which can be activated with in the extension settings with the option *Enable demo scripts*

The key should follow the TYPO3 convention of tx_myext_function.

Inside the array, the following options are available:

| Option        | Required | Description                                  |
| ------------- |:--------:| ---------------------------------------------|
| label         | yes      | Label of the script, can be a LLL: reference |
| script        | yes      | The script to execute                        |
| icon          | no       | Icon identifier shown in the dropdown        |
| reloadBackend | no       | Reload the backend after the given seconds   |


If `reloadBackend` is given, the number is the count of seconds, after which the backend is reloaded, after a script
finished. This is useful if a script changes the data visible in the backend, like a product importer.

### Permissions

By default, administrators see all scripts. This can be disabled in the extension settings with the option *Disable
always access for administators*. Then the access must be granted with TSConfig settings, like to editors.

Editors can only execute scripts which are in a comma separated list in the TSConfig key `tx_runscript.allowed`. The key
of the configuration array must be in this list for a script to be executable.

Example:

```
# Always allow those two:
tx_runscript.allowed = tx_shop_import_products, tx_shop_export_orders

# Editors with Backend Group 3 can also run this one:
[like(","~backend.user.userGroupList~",", "*,3,*")]
    tx_runscript.allowed := addToList(tx_shop_clean_expired_carts)
[global]
```

## License

MIT License, see [LICENSE](./LICENSE) or <https://opensource.org/licenses/MIT>

Logo and icon from [Bootstrap Icons](https://icons.getbootstrap.com/), released under the [MIT](https://github.com/twbs/icons/blob/main/LICENSE.md) license.

[TYPO3](https://typo3.org) is released under the GNU/GPL License. Please see <https://github.com/TYPO3/TYPO3.CMS/blob/master/LICENSE.txt> for details
