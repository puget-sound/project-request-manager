<?php namespace App\Providers;

use View;
use Illuminate\Support\ServiceProvider;
use Carbon\Carbon;

class ComposerServiceProvider extends ServiceProvider {

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('*', function($view)
        {
          $sprints = \App\Sprints::get();
          $current_sprint = '';
          $today = Carbon::today();

          for ($i = 0; $i < count($sprints); $i++) {
    				if ($today >= $sprints[$i]->sprintStart && ($today <= $sprints[$i]->sprintEnd || $today < $sprints[$i+1]->sprintStart)){
              $current_sprint = $sprints[$i]->sprintNumber;
            }
          }

         $view->with('menu_owners', \App\Owners::where('active', '=', 'Active')->orderBy('name')->get())->with('current_sprint', $current_sprint);
        });
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }

}
