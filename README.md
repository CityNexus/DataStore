# DataStorage

This package is the data storage service for CityNexus. The data store manages the creation and importation of raw data into CityNexus.

## Core Services

### Create New Dataset

`DataStore::create(array $setttings);`

#### Setting Array

- name [required]
Human readable dataset name

- table_name
An all lowercase alphanumeric table name, will be prepended with `cnds_` before being used at the dataset name

- schema [required]
An array with each element representing an different data element
    - name [required]
    Human readable data point name
    
    - key [unique]
    An all lowercase alphanumeric name for the data point which much be unique in the data set
    
    - type
    Assigns the data storage type: `string` [default], `text`, `integer`, `float`, `datetime`, `boolean`
