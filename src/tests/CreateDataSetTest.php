<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CreateDatasetTest extends TestCase
{

    use DatabaseTransactions;

    public function __construct()
    {
        $this->class = new CityNexus\DataStore\DataStore();
    }
    
    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testCreate()
    {
        $new_dataset =
            [
                'name' => 'Test Dataset',
                'table_name' => 'test_dataset',
                'schema' =>
                    [
                        [
                            'name' => 'Full Address'
                        ],
                        [
                            'name' => 'Data Point',
                        ]
                    ]
            ];

        $this->class->create($new_dataset);

        $this->assertTrue(\Illuminate\Support\Facades\Schema::hasTable('cnds_test_dataset'), "New table was created");
    }

    /**
     *
     * @expectedException \Exception
     *
     * @return void
     *
     */
    public function testCreateWithoutName()
    {
        $this->class->create([]);
    }


    /**
     *
     * @expectedException \Exception
     *
     * @return void
     *
     */
    public function testCreateWithoutNonuniqueTable()
    {
        \Illuminate\Support\Facades\Schema::create('cnds_existing_table_name', function ($table) {
        $table->increments('id');
        });

        $this->class->create([
            'name' => 'existing_table_name',
            'table_name' => 'cnds_existing_table_name'
        ]);
    }

}
