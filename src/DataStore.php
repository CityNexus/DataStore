<?php


namespace CityNexus\DataStore;


class DataStore
{
    /**
     * @param array $settings
     */
    public function create(array $settings)
    {
        $tableBuilder = new TableBuilder();

        $tableBuilder->makeTable($settings);
    }
}