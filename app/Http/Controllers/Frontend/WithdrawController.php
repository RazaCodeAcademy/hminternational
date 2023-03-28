<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AccountType;
use App\Models\PaymentMethod;
use App\Models\IndirectEarning;
use App\Models\TotalEarning;
use App\Models\DirectEarning;
use App\Models\Withdraw;
use App\Models\Hit;
use App\Models\HitBonus;
use App\Models\CurrentEarning;
use Auth;

class WithdrawController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()

    {
        $user = Auth::user();
        $withdraw_amount = Withdraw::where('user_id', Auth::user()->id)->sum('amount');
        $current = CurrentEarning::where('user_id', Auth::user()->id)->first();
        $current_balance = $current ? $current->amount : 0;
        $bonus = HitBonus::where('user_id', Auth::user()->id)->first();
        $bonus_balance = $bonus ? $bonus->amount : 0;
        $direct_earning = DirectEarning::where('user_id', Auth::user()->id)->first();
        $payment_methods = PaymentMethod::all();
        return view('frontend.pages.withdraw.index',compact('withdraw_amount','payment_methods','user','current_balance', 'bonus_balance'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $image = $this->downloadScreenshot('withdraw');
            // return response()->download($image);
        $current = CurrentEarning::where('user_id', Auth::user()->id)->first();
        $bonus = HitBonus::where('user_id', Auth::user()->id)->first();
        $current_balance = $current ? $current->amount : 0;
        $bonus_balance = $bonus ? $bonus->amount : 0;
        $current_bonus_balance = $current_balance + $bonus_balance;

        if($current_bonus_balance < $request->amount){
            return response()->json([
                'success' => true,
                'withdraw' => '',
                'message' => 'You do not have sufficient balance for this transaction'
            ]);
            // return redirect()->back()->with("error", "You do not have sufficient balance for this transaction");
        }

        $data =[
            'payment_method' => $request->payment_method,
            'amount' => $request->amount,
            'user_id' => Auth::user()->id,

        ];
        $withdraw = Withdraw::create($data);

        if($request->amount > $current_balance){
            $extra_amount = $request->amount - $current_balance;
            $current->amount -= $request->amount - $extra_amount;
            $current->save();

            $bonus_amount = $request->amount - $current_balance;
            $bonus->amount -= $bonus_amount;
            $bonus->save();

            $hit = Hit::where('user_id', Auth::user()->id)->first();
            $hit->number -= $bonus_amount/2;
            $hit->save();
        }else{
            $current->amount -= $request->amount;
            $current->save();
        }
        if($withdraw){
            // $image = $this->downloadScreenshot('withdraw');
            return response()->json([
                'success' => true,
                'withdraw' => $withdraw,
                'message' => 'Withdraw request has been sent successfuly'
            ]);
            // return redirect()->route('dashboard')->with('success', 'Your Request has Sended Successfully!');
        }

        return response()->json([
            'success' => false,
            'withdraw' => '',
            'message' => 'Withdraw request has not been sent successfuly'
        ]);
    }

    public function downloadScreenshot($withdraw){
        // Create a blank image with a red background
        $image = imagecreatetruecolor(400, 600);
        $background = imagecolorallocate($image, 255, 255, 255);
        imagefill($image, 0, 0, $background);

        // Set the text color of image
        $heading_color = imagecolorallocate($image, 0, 0, 0);
        $text_color = imagecolorallocate($image, 16, 16, 16);
        $amount_color = imagecolorallocate($image, 51, 204, 51);

        $date = date('d/m/H:i:s');

        imagestring($image, 5, 10, 180,  $date, $heading_color);

        imagestring($image, 5, 10, 210,  "Send by: M Husnain Raza", $heading_color);
        imagestring($image, 4, 10, 230,  "Sponser I'd: 1245", $text_color);

        imagestring($image, 5, 10, 260,  "Receiving Account", $heading_color);
        imagestring($image, 4, 10, 280,  "Jaazcash", $text_color);

        imagestring($image, 5, 10, 310,  "Send to (admin)", $heading_color);
        imagestring($image, 4, 10, 330,  "13242532", $text_color);

        imagestring($image, 5, 10, 360,  "Withdraw fee", $heading_color);
        imagestring($image, 4, 10, 380,  "$0", $text_color);

        imagestring($image, 5, 10, 410,  "Withdraw amount", $amount_color);
        imagestring($image, 4, 10, 430,  "$435", $text_color);

        $width = 100;
        $height = 100;

        // Load the blue square image from a file
        $source_image = imagecreatefrompng('public/site-logo.png');

        // Create a new blank image with the desired width and height
        $new_image = imagecreatetruecolor($width, $height);
        $background = imagecolorallocate($new_image, 255, 255, 255);
        imagefill($new_image, 0, 0, $background);

        // Copy and resize the source image to the new image with the desired width and height
        imagecopyresized($new_image, $source_image, 0, 0, 0, 0, $width, $height, imagesx($source_image), imagesy($source_image));

        // imagefilter($new_image, IMG_FILTER_COLORIZE, 100, 0, 0);
        // Copy the blue square image to the top left corner of the red square image
        imagecopy($image, $new_image, 100, 30, 0, 0, 150, 150);

        // Set the content type header to PNG
        header('Content-Type: image/png');

        // Create the images folder if it doesn't exist
        if (!file_exists('public/screenshot')) {
            mkdir('public/screenshot');
        }

        $screenshot = "public/screenshot/".time().".png";

        imagepng($image, $screenshot);

        // Free up memory
        imagedestroy($image);

        return $screenshot;

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
