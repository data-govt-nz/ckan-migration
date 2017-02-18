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

`ckanapi load organizations -I ./organisations.jsonld -r CKAN_URL -a API_KEY
`

## Group
### Loading group taxonomy
`ckanapi load groups -I ./groups.jsonld -r CKAN_URL -a API_KEY
`
## Datasets
TODO - loading and dumping of datasets

## License
See [LICENSE.md](LICENSE.md)

## Contributing
See [CONTRIBUTING.md](CONTRIBUTING.md)

## Maintainer
 - Cam Findlay <cam.findlay@dia.govt.nz>
 - Data.govt.nz team <info@data.govt.nz>
