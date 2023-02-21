<?php
namespace Tests\Browser;
use Livewire\Livewire;
use App\Models\User;
use App\Http\Livewire\AddCartItem;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Tests\CreateData;
use App\Models\City;
use App\Models\Department;
use App\Models\District;


class CreateOrdersTest extends DuskTestCase
{
    use DatabaseMigrations ,CreateData;

   /** @test */
   public function shippingFormIsSeenWhenChosen() 
   {

    $this->browse(function (Browser $browser) {
        $product = $this->createProduct();

        Livewire::test(AddCartItem::class, ['product' => $product])
            ->call('addItem', $product);

        $browser->loginAs(User::factory()->create())
        ->pause(500)
        ->visit('/orders/create')->check('@home')
        ->pause(500)
        ->assertVisible('@form');

        });
   }

   /** @test */
   public function shippingFormIsNotSeenWhenUnchosen() 
   {

    $this->browse(function (Browser $browser) {
        $product = $this->createProduct();

        Livewire::test(AddCartItem::class, ['product' => $product])
            ->call('addItem', $product);

        $browser->loginAs(User::factory()->create())
        ->pause(500)
        ->visit('/orders/create')->check('@shop')
        ->pause(500)
        ->assertMissing('@form');


        });
   }


    /** @test */
    public function DepartmentsSelectContainsAllDepartments() 
    {
        $this->browse(function (Browser $browser) {
            $product = $this->createProduct();
            Livewire::test(AddCartItem::class, ['product' => $product])
                ->call('addItem', $product);

            $browser->loginAs(User::factory()->create());

            $departments = Department::factory(2)->create()->pluck('id')->all();

            $browser->visit('/orders/create')->assertSelectHasOptions('departments', $departments);
        });
        
    }

    /** @test */
    
    public function CitiesSelectContainsCorrectCities() 
    {
        $this->browse(function (Browser $browser) {
            $product = $this->createProduct();
            Livewire::test(AddCartItem::class, ['product' => $product])
                ->call('addItem', $product);

            $browser->loginAs(User::factory()->create());

            $departments = Department::factory(2)->create();
            $cities1= City::factory(2)->create([
                'department_id'=> $departments[0]->id
            ]);
            $cities2= City::factory(2)->create([
                'department_id'=> $departments[1]->id
            ]);
            $idCities1 = $cities1->pluck('id')->all();
            $idCities2 = $cities2->pluck('id')->all();

            $browser->visit('/orders/create')
            ->check('@home')
            ->select('departments', 2)
            ->pause(1000)
            ->assertSelectHasOptions('cities', $idCities2)
            ->assertSelectMissingOptions('cities', $idCities1);
        });
        
    }
    

    /** @test */
    
    public function districtsSelectContainsCorrectDistricts()
    {
        $this->browse(function (Browser $browser) {
            $product = $this->createProduct();
            Livewire::test(AddCartItem::class, ['product' => $product])
                ->call('addItem', $product);

            $browser->loginAs(User::factory()->create());

            $departments = Department::factory(2)->create();
            $cities= City::factory(2)->create([
                'department_id'=> $departments[0]->id
            ]);
            $districts1 = District::factory(2)->create([
                'city_id'=>$cities[0]->id
            ]);
            $districts2 = District::factory(2)->create([
                'city_id'=>$cities[1]->id
            ]);

            $idDistricts1 = $districts1->pluck('id')->all();
            $idDistricts2 = $districts2->pluck('id')->all();

            $browser->visit('/orders/create')
            ->check('@home')
            ->select('departments', 1)
            ->pause(1000)
            ->select('cities', 2)
            ->pause(1000)
            ->assertSelectHasOptions('districts', $idDistricts2)
            ->assertSelectMissingOptions('districts', $idDistricts1);

        });
    }

    
}