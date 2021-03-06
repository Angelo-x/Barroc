<?php

namespace App\Http\Controllers;

use App\Customer;
use App\User;
use App\Quotation;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function dashboard() 
    {
        return view('customer.index');
    }

    public function quotes()
    {
        $quotes = \App\Quotation::where('customer_id', \Auth::id())->paginate(15);

        return view('customer.quotes.index', ['quotes' => $quotes]);
    }

        public function quotesShow($id)
        {
            $quote = \App\Quotation::where('id', $id)->with('purchase', 'purchase.supplies')->first();

            $customer = \App\User::find($quote->customer_id);
            $user = \App\User::find($quote->sales_id);

            return view('customer/quotes/show', ['quote' => $quote, 'user' => $user, 'customer'=> $customer] );
        }

    public function leases()
    {
        $leases = \App\Lease::where('customer_id', \Auth::id())->paginate(15);

        return view('customer.leases.index', ['leases' => $leases]);
    }

        public function leasesShow($id)
        {
            $lease = \App\Lease::where('id', $id)->with('supplies', 'user', 'finance')->first();

            return view('customer/leases/show', ['lease' => $lease] );
        }    

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::where('role_id', '=', 7)->paginate(15);

        return view('sales/customers/index', ['users' => $users]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('sales/customers/create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->validate($request, [
            'companyName' => 'required',
            'email' => 'required',
            'password' => 'required',
            'bkr' => 'boolean',
        ]);

        // Insert new user in database
        $user = new User();
        $user->role_id = 7;
        $user->name = $request->companyName;
        $user->email = $request->email;
        $user->password = password_hash($request->password, PASSWORD_DEFAULT);
        $user->bkr = $request->has('bkr');
        $user->save();

        // Insert new customer in database
        /*$customer = new Customer();
        $customer->user_id = $user->id;
        $customer->save();*/

        $id = $user->id;

        return redirect()->route('customers.show', $id);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $customer)
    {
        return view('sales/customers/show', ['user' => $customer]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function edit(Customer $customer)
    {
        return view('customer.edit', ['customer' => $customer]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Customer $customer)
    {

        $this->validate($request, [
            'companyName' => 'required',
            'email' => 'required',
            'password' => 'required',
            'bkr' => 'boolean',
        ]);

        // Insert new user in database
        $user = \App\User::find($customer->id);
        $user->name = $request->companyName;
        $user->email = $request->email;
        $user->password = $request->password;
        $user->bkr = $request->bkr;
        $user->save();

        $id = $customer->id;

        return redirect()->route('customers.show', $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer)
    {
        //
    }
}
