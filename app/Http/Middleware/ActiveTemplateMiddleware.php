<?php

namespace App\Http\Middleware;

use App\Constants\Status;
use App\Models\Page;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class ActiveTemplateMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $viewShare['activeTemplate']     = activeTemplate();
        $viewShare['activeTemplateTrue'] = activeTemplate(true);
        view()->share($viewShare);

        view()->composer(['Template::partials.header', 'Template::partials.footer'], function ($view) {
            $view->with([
                'pages' => Page::where('is_default', Status::NO)->where('tempname', activeTemplate())->orderBy('id', 'DESC')->get()
            ]);
        });

        View::addNamespace('Template',resource_path('views/templates/'.activeTemplateName()));
        return $next($request);
    }
}
