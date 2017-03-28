# Data.govt.nz migration scripts

In Feb/Mar 2017 data.govt.nz dataset portal was moved to a new CKAN data portal platform. To make sure migration was replicable and not just a copy and paste job (prone to data loss and error), we made use of the built in CKAN API and a number of pragmatic scripts to wrangle the data loading. As a result, we were able to migrate faster and make use of other existing government services (like the govt.nz organisation API to populate our initial list of data releasing organisations).

## Getting started
Install the python based CKANAPI Command Line Interface (CLI). See https://github.com/ckan/ckanapi
This allows you at use the CKAN API to push and pull data directly out of any CKAN installation (making it very useful for migration work).

CKANAPI export are stored in a format called JSON Linked Data (jsonld). See http://json-ld.org/

## Organisations
### Building the json-ld

```
$ cd /path/to/script
$ php ./organisations.php
```

### Loading to CKAN

`ckanapi load organizations -I ./organisations.jsonld -r CKAN_URL -a API_KEY`

### Updating the status of an existing and not active organisations

 1. Login as sysadmin and find the non-active organsiation (most NZ govt orgs exist already as they were loaded from a full list of all govt orgs).

 2. Copy the id of the org eg `marlborough-district-council`.

 3. Run the following ckanapi command `ckanapi action organization_update id=marlborough-district-council state=active -r CKAN_URL -a API_KEY`



## Group
### Loading group taxonomy
`ckanapi load groups -I ./groups.jsonld -r CKAN_URL -a API_KEY
`
## Datasets
### Bulk delete of all datasets
Note: this does not `purge` the datasets just set them to status: deleted

`ckanapi action package_list -j -r CKAN_URL | ckanapi delete datasets -r CKAN_URL -a API_KEY`

This pipes a list of existing datasets into the ckanapi delete command.

### Bulk export of datasets

`ckanapi dump datasets -O /path/to/datasets.jsonld --all -r CKAN_URL`

Dumps out all datasets and harvester configs into a single JSON linked data formate file.

### Bulk load of datasets

`ckanapi load datasets -I /path/to/datasets.jsonld -r CKAN_URL -a API_KEY`

Loads datasets into CKAN.

### Gotchas
 - You'll need to have already loaded in the groups and organisations before loading in datasets (due to the data relations).
 - Harvesters can sometimes run during the loading process which can cause duplicate datasets (harvester runs before a dataset is imported).
 - CKANAPI imported resources can sometimes not fire the datapusher and be added in the datastore for data exploration. In this case you can make use of the `datastore_create` API call using a list of known erroring resource id numbers. It is possible that this occurs due to the imported resource property `datastore_active` being `true` when in a new CKAN instance the datastore is empty for this resource (yet to test this assumption).

## License
See [LICENSE.md](LICENSE.md)

## Contributing
See [CONTRIBUTING.md](CONTRIBUTING.md)

## Maintainer
 - Cam Findlay <cam.findlay@dia.govt.nz>
 - Data.govt.nz team <info@data.govt.nz>
