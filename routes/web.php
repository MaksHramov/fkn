<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\User;
function shopCart(){return DB::table('products')->whereIn('id',array_keys(session('cart',[])))->get()->map(function($p){$p->quantity=session('cart',[])[$p->id];return $p;});}
Route::get('/',fn()=>view('shop',['products'=>DB::table('products')->orderBy('id')->get()]));
Route::get('/products/{id}',fn($id)=>view('product',['product'=>DB::table('products')->find($id)??abort(404)]));
Route::post('/cart/{id}',function(Request $r,$id){abort_unless(DB::table('products')->where('id',$id)->exists(),404);$data=$r->validate(['quantity'=>'nullable|integer|min:0|max:20']);$cart=session('cart',[]);$cart[$id]=isset($data['quantity'])?(int)$data['quantity']:min(20,($cart[$id]??0)+1);if(!$cart[$id])unset($cart[$id]);session(['cart'=>$cart]);return back()->with('notice','Корзина обновлена');});
Route::get('/cart',fn()=>view('cart',['items'=>shopCart()]));
Route::get('/login',fn()=>view('auth',['mode'=>'login']))->middleware('guest')->name('login');
Route::post('/login',function(Request $r){$data=$r->validate(['login'=>'required|string','password'=>'required|string']);if(!Auth::attempt($data))return back()->withErrors(['login'=>'Неверный логин или пароль.'])->onlyInput('login');$r->session()->regenerate();return redirect()->intended('/orders');})->middleware(['guest','throttle:10,1']);
Route::get('/register',fn()=>view('auth',['mode'=>'register']))->middleware('guest');
Route::post('/register',function(Request $r){$data=$r->validate(['login'=>['required','string','min:3','max:32','regex:/^[A-Za-z0-9_]+$/','unique:users,login'],'password'=>'required|string|min:8|max:72|confirmed']);$user=User::create(['name'=>$data['login'],'login'=>$data['login'],'email'=>(string)Str::uuid().'@users.invalid','password'=>Hash::make($data['password'])]);Auth::login($user);$r->session()->regenerate();return redirect()->intended('/orders');})->middleware(['guest','throttle:10,1']);
Route::post('/logout',function(Request $r){Auth::logout();$r->session()->invalidate();$r->session()->regenerateToken();return redirect('/');})->middleware('auth');
Route::post('/checkout',function(Request $r){$data=$r->validate(['city'=>'required|string|min:2|max:100']);$items=shopCart();if($items->isEmpty())return back()->withErrors(['cart'=>'Добавьте товары в корзину.']);$ref=(string)Str::uuid();DB::transaction(function()use($data,$items,$ref,$r){DB::table('orders')->insert(['user_id'=>$r->user()->id,'name'=>$r->user()->login,'email'=>$r->user()->email,'address'=>$data['city'],'city'=>$data['city'],'reference'=>$ref,'total'=>$items->sum(fn($p)=>$p->price*$p->quantity),'items'=>json_encode($items),'created_at'=>now(),'updated_at'=>now()]);});session()->forget('cart');return redirect('/orders')->with('order',$ref);})->middleware(['auth','throttle:10,1']);
Route::get('/orders',fn()=>view('orders',['orders'=>DB::table('orders')->where('user_id',Auth::id())->latest()->get()]))->middleware('auth');
