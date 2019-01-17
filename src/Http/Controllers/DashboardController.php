<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 23.08.18
 * Time: 20:51
 */

namespace ARudkovskiy\Admin\Http\Controllers;


use ARudkovskiy\Admin\Container\AdminContainerInterface;
use ARudkovskiy\Admin\Models\User;
use ARudkovskiy\Admin\Services\Repository;
use Illuminate\Http\Request;

class DashboardController extends Controller
{

    public function signIn()
    {
        return view('@admin::auth.signin');
    }

    public function signInCheck(Request $request)
    {
        $username = $request->get('username');
        $password = $request->get('password');

        $user = User::where('username', $username)->firstOrFail();
        $hash = implode('@', [ $user->id, $password ]);

        if (\Hash::check($hash, $user->password)) {
            session()->put('user_id', $user->id);
            $redirectTo = session()->get('_redirect_to', null);
            if ($redirectTo !== null) {
                session()->remove('_redirect_to');
                return redirect($redirectTo);
            }
            return redirect()->route('admin.dashboard');
        } else {
            return redirect()->back();
        }
    }

    public function signOut()
    {
        session()->flush();
        return redirect()->back();
    }

    public function select2(Request $request)
    {
        if (strlen($request->get('q')) < 1) {
            return [
                'results' => []
            ];
        }

        $adminContainer = app()->make(AdminContainerInterface::class);
        $field = $request->get('value');
        $id = $request->get('id');
        $entityName = $request->get('entity');
        $entity = $adminContainer->getEntity($entityName);
        $repository = new Repository($entity);
        $objects = $repository->findLike($field, $request->get('q'));

        $objects = $objects->map(function ($object) use ($id, $field) {
            return [
                'id' => $object->{$id},
                'text' => $object->{$field}
            ];
        });

        return [
            'results' => $objects
        ];
    }
    
}