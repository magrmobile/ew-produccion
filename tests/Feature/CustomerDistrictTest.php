<?php

namespace Tests\Feature;

use App\Customer;
use App\Http\Controllers\CustomerController;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class CustomerDistrictTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
        DB::purge('sqlite');
        Schema::create('cat008', function (Blueprint $table) {
            $table->string('id');
            $table->string('departamento');
            $table->string('municipio');
            $table->string('valor');
        });
        Schema::create('customers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nit');
            $table->timestamps();
        });
        require_once database_path('migrations/2026_10_02_000001_add_distrito_to_customers_table.php');
        (new \AddDistritoToCustomersTable())->up();
        DB::table('cat008')->insert(['id' => '02', 'departamento' => '06', 'municipio' => '01', 'valor' => 'Distrito de prueba']);
    }

    private function valid(array $data)
    {
        $method = new \ReflectionMethod(CustomerController::class, 'districtRules');
        $method->setAccessible(true);
        $rules = $method->invoke(new CustomerController(), Request::create('/customers', 'POST', $data));
        return Validator::make($data, $rules)->passes();
    }

    public function test_district_must_belong_to_selected_department_and_municipality()
    {
        $this->assertTrue($this->valid(['departamento' => '06', 'municipio' => '01', 'distrito' => '02']));
        $this->assertFalse($this->valid(['departamento' => '05', 'municipio' => '01', 'distrito' => '02']));
        $this->assertFalse($this->valid(['departamento' => '06', 'municipio' => '02', 'distrito' => '02']));
        $this->assertFalse($this->valid(['departamento' => '06', 'municipio' => '01', 'distrito' => '99']));
        $this->assertFalse($this->valid(['departamento' => '06', 'municipio' => '01', 'distrito' => null]));
        $this->assertTrue($this->valid(['departamento' => null, 'municipio' => null, 'distrito' => null]));
    }

    public function test_district_can_be_created_and_updated_after_migration()
    {
        $customer = Customer::create(['nit' => '12345678901234', 'distrito' => '02']);
        $this->assertSame('02', $customer->fresh()->distrito);
        $customer->fill(['distrito' => null])->save();
        $this->assertNull($customer->fresh()->distrito);
    }
}
