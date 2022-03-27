<?php 
namespace App\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

/**
 * @property Request request
 */
class BaseRequest
{
    protected $id;

    /**
     *  A list of all post validation errors
     * @var array
     */
    protected $errors = [];

    /**
     * BaseRequest constructor.
     * @param string|integer|null $id
     * @throws ValidationException
     */
    public function __construct()
    {
        if (!isset($this->request)) {
            $this->request = app('request');
        }

        $this->validate();
    }

    /**
     * @throws ValidationException
     */
    public function validate()
    {
        $requestAll = method_exists($this, 'all') ? $this->all() : $this->request->all();
        $validator = \Validator::make($requestAll, $this->rules(), $this->messages(), $this->attributes());

        $pass = !empty($this->rules()) ? $validator->passes() : true;

        if (!$pass) {
            throw new ValidationException($validator);
        }

        $this->postValidate();

        if (!empty($this->errors)) {
            throw ValidationException::withMessages($this->errors);
        }
    }

    public function postValidate()
    {
        return true;
    }

    public function rules()
    {
        return [];
    }

    public function attributes()
    {
        return [];
    }

    public function messages()
    {
        return [];
    }

    function __call($method, $params)
    {
        if (!isset($this->request)) {
            $this->request = app('request');
        }
        return call_user_func_array([$this->request, $method], $params);
    }

    function __get($attr)
    {
        if ($this->request->has($attr)) {
            return $this->request->input($attr);
        }
        if (property_exists($this->request, $attr)) {
            return $this->request->$attr;
        }
        return null;
    }

    public function get($attr, $default = null)
    {
        return $this->request->input($attr, $default);
    }

}
