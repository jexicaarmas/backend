<?php

use Illuminate\Database\Seeder;
use App\Models\User; 
use App\Models\Product; 

class InitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      /*Ejecuta passport install en la linea de comandos*/
      Artisan::call('passport:install', ['--force' => true]);

      $this->faker = Faker\Factory::create();
      $this->users();    
      $this->products();  

    }

    private function users() 
    {  
      $user = new User; 
      $user->name  = $this->faker->name(); 
      $user->email = 'jaay0830@gmail.com'; 
      $user->password = bcrypt('123456'); 
      $user->save(); 
      $this->command->warn( "OK\t ".__METHOD__ );
    }

    private function products() 
    {
      for ($i=0; $i <=20 ; $i++) { 
        $product = new Product; 
        $product->reference    = $this->faker->unique()->randomNumber($nbDigits = 6); 
        $product->name         = $this->faker->word(); 
        $product->description  = $this->faker->text($maxNbChars = 200); 
        $product->quantity     = $this->faker->randomNumber($nbDigits = 4); 
        $product->image        = $this->faker->imageUrl($width = 640, $height = 480); 
        $product->save(); 
      } 
      $this->command->warn( "OK\t ".__METHOD__ );
    }
}
