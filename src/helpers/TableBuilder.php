<?php


namespace CityNexus\DataStore;


use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Psy\Exception\ErrorException;
use Symfony\Component\VarDumper\Caster\ExceptionCaster;

class TableBuilder
{

    public function __construct()
    {
        $this->helper = new Helpers();
    }
    /**
     * @param array $settings
     */
    public function makeTable(array $settings)
    {
        // Check for required elements of settings

            // Check for name

                if(!isset($settings['name']))
                {
                    throw new \Exception('NAME element required to create data set');
                }

            // Check for table_name or create one from name

                if(isset($settings['table_name']))
                {
                    $settings['table_name'] = $settings['name'];
                }

                $settings['table_name'] = $this->helper->cleanName($settings['table_name']);

                // Check if table_name needs to be prefixed with cnds_

                if(substr($settings['table_name'], 0, 5) != 'cnds_')
                {
                    $settings['table_name'] = 'cnds_' . $settings['table_name'];
                }

                // Check if table name is already in use

                if(Schema::hasTable($settings['table_name']))
                {
                    throw new \Exception('TABLE_NAME provided is already in use');
                }

            // Check for schema elements and if each elements has a name, unique key, and valid type

                if(!isset($settings['schema']))
                {
                    throw new \Exception('No schema elements were passed to method');
                }

                // Check for schema elements
                $keys = array();
                foreach($settings['schema'] as $k => $i)
                {
                    if(!isset($i['name']))
                    {
                        throw new \Exception('All schema elements must have name');
                    }

                    if(isset($i['key']))
                    {
                        $i['key'] = $this->helper->cleanName($i['key']);
                    }
                    else
                    {
                        $i['key'] = $this->helper->cleanName($i['name']);
                    }

                    if($i['key'] == 'id' or $i['key'] == 'created_at' or $i['key'] == 'updated_at' or $i['key'] == 'processed_at')
                    {
                        throw new \Exception('Illegal key value');
                    }

                    if(isset($keys[$i['key']]))
                    {
                        throw new \Exception('Non-unique element keys are not allowed');
                    }

                    if(!isset($i['type']) or $i['type'] == null)
                    {
                        $i['type'] = 'string';
                    }
                    elseif($i['type'] != 'string' && $i['type'] != 'text' && $i['type'] != 'integer' && $i['type'] != 'float' && $i['type'] != 'datetime' && $i['type'] != 'boolean')
                    {
                        throw new \Exception('Illegal schema element data type');
                    }

                    $settings['schema'][$k] = $i;
                }

        // Try creating table
        try
        {
            $fields = $settings['schema'];

            Schema::create($settings['table_name'], function (Blueprint $table) use ($fields) {
                // Create table's index id file
                $table->increments('id');
                $table->integer('upload_id');

                foreach ($fields as $field) {
                    $table->$field['type']($field['key'])->nullable();
                }

                $table->json('raw')->nullable();
                $table->dateTime('processed_at')->nullable();
                $table->timestamps();
            });
        }
        catch(\Exception $e)
        {
            return $e;
        }

        return $settings;
    }
}