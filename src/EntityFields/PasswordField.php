<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 04.08.18
 * Time: 21:03
 */

namespace ARudkovskiy\Admin\EntityFields;


use Illuminate\Http\Request;

class PasswordField extends TextField
{

    protected $type = 'password';

    public function __construct()
    {
        parent::__construct();
        $this->setIsUpdatingManually(true);
        $this->setHandleAfterSave(true);
    }

    public function renderEditable()
    {
        return parent::renderEditable()
            ->with('value', '');
    }


    public function handleRequest(Request $request, $entityObject)
    {
        if ($request->get('password', null) === null) {
            return;
        }

        parent::handleRequest($request, $entityObject);
        $hash = [ $entityObject->id, $this->value ];
        $hash = implode('@', $hash);
        $entityObject->{$this->name} = \Hash::make($hash);
    }


}