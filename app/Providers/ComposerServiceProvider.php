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

          foreach ($sprints as $sprint) {
    				if (\Carbon\Carbon::now() >= $sprint->sprintStart && \Carbon\Carbon::now() <= $sprint->sprintEnd ) {
              $current_sprint = $sprint->sprintNumber;
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
