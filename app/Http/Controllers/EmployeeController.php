<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Position;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    //public function index()
    //{
    //    $pageTitle = 'Employee List';
    //    return view('employee.index', ['pageTitle' => $pageTitle]);
    //}
    public function index()
    {
        $pageTitle = 'Employee List';
        // QUERY BUILDER
        //$employees = DB::select(' select *, employees.id as employee_id, positions.name as position_name from employees left join positions on employees.position_id = positions.id ');

        // ELOQUENT
        $employees = Employee::all();
        return view('employee.index', [
            'pageTitle' => $pageTitle,
            'employees' => $employees
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    //public function create()
    //{
    //    $pageTitle = 'Create Employee';
    //    return view('employee.create', compact('pageTitle'));
    //}
    public function create()
    {
        $pageTitle = 'Create Employee';

        // ELOQUENT
        $positions = Position::all();
        return view('employee.create', compact('pageTitle', 'positions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $messages = [
            'required' => ':Attribute harus diisi.',
            'email' => 'Isi :attribute dengan format yang benar',
            'numeric' => 'Isi :attribute dengan angka'
        ];
        $validator = Validator::make($request->all(), [
            'firstName' => 'required',
            'lastName' => 'required',
            'email' => 'required|email',
            'age' => 'required|numeric',
        ], $messages);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }


        // INSERT QUERY
        //DB::table('employees')->insert([
        //   'firstname' => $request->firstName,
        //    'lastname' => $request->lastName,
        //    'email' => $request->email,
        //    'age' => $request->age,
        //    'position_id' => $request->position,
        //]);

        // ELOQUENT
        $employee = new Employee;
        $employee->firstname = $request->firstName;
        $employee->lastname = $request->lastName;
        $employee->email = $request->email;
        $employee->age = $request->age;
        $employee->position_id = $request->position;
        $employee->save();

        return redirect()->route('employees.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pageTitle = 'Employee Detail';
        // QUERY BUILDER 
        //$employee = DB::table('employees')
        //    ->leftJoin('positions', 'employees.position_id', '=', 'positions.id')
        //    ->select('employees.*', 'employees.id as employee_id', 'positions.name as position_name')
        //    ->where('employees.id', $id)
        //    ->first();

        // ELOQUENT
        $employee = Employee::find($id);

        return view('employee.show', compact('pageTitle', 'employee'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $pageTitle = 'Edit Employee';

        // Ambil data employee berdasarkan ID
        //$employee = DB::table('employees')->where('id', $id)->first();
        //$positions = DB::table('positions')->get();

        // ELOQUENT
        $positions = Position::all();
        $employee = Employee::find($id);

        return view('employee.edit', compact('pageTitle', 'employee', 'positions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $messages = [
            'required' => ':Attribute harus diisi.',
            'email' => 'Isi :attribute dengan format yang benar',
            'numeric' => 'Isi :attribute dengan angka'
        ];

        $validator = Validator::make($request->all(), [
            'firstName' => 'required',
            'lastName' => 'required',
            'email' => 'required|email',
            'age' => 'required|numeric',
        ], $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // UPDATE QUERY
        //DB::table('employees')
        //   ->where('id', $id)
        //  ->update([
        //        'firstname' => $request->firstName,
        //        'lastname' => $request->lastName,
        //        'email' => $request->email,
        //        'age' => $request->age,
        //        'position_id' => $request->position,
        //    ]);

        // ELOQUENT
        $employee = Employee::find($id);
        $employee->firstname = $request->firstName;
        $employee->lastname = $request->lastName;
        $employee->email = $request->email;
        $employee->age = $request->age;
        $employee->position_id = $request->position;
        $employee->save();

        return redirect()->route('employees.index')->with('success', 'Employee updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //QUERY BUILDER
        //DB::table('employees')
        //    ->where('id', $id)
        //    ->delete();

        // ELOQUENT
        Employee::find($id)->delete();

        return redirect()->route('employees.index');
    }
}
