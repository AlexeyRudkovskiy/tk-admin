<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 30.09.18
 * Time: 20:43
 */

namespace ARudkovskiy\Admin\Http\Controllers;


use Illuminate\Http\Request;

class ConfigController extends Controller
{

    public function index(Request $request)
    {
        if (session()->has('__refresh')) {
            session()->remove('__refresh');
            return response('<meta http-equiv="refresh" content="3;url=' . url($request->getRequestUri()) . '" /><p>Оновлення файлу конфігурацій</p>');
        }

        $defaults = config('configs');

        if (!array_has($defaults, 'director')) {
            $defaults['director'] = null;
        }

        return view('@admin::config.index')
            ->with('configs', config('admin.configs'))
            ->with('default', $defaults);
    }

    public function save(Request $request)
    {
        $config = $request->get('config');
        $config = var_export($config, true);
        $config = '<?php' . PHP_EOL . 'return ' . $config . ';' . PHP_EOL;

        file_put_contents(config_path() . '/configs.php', $config);

        session()->flash('__refresh', 1);

        return redirect()->back();
    }

}