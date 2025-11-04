<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\File;
use Illuminate\Support\Facades\Hash;
use Auth;
use App\Models\Menu;
use Illuminate\Support\Facades\Log;
//use Illuminate\Support\Facades\Validator;

class MenuSequenceController extends Controller
{
    public function index(Request $request) 
    {
        $perPage = $request->input('perPage', 50);
        $menus = Menu::where('status', '=', 1)
        ->where('sequence_id', '>', 0)
        ->where('menu_id', '=', 0)
        ->where('type_id', '=', 1)
        ->where('category_id', '=', 2)
        ->orderBy('sequence_id', 'asc')
        ->paginate($perPage);


        $menus->setPath(asset('/menusequence'));

        // /print_r($rashiphal);
        // Build query parameters for pagination links
        $queryParams = $request->except('page');
        if ($perPage != 20) {
            $queryParams['perPage'] = $perPage;
        }

        // Set the pagination path with query parameters
        $menus->setPath(asset('/menusequence') . '?' . http_build_query($queryParams));
       
       return view('admin/menuControl')->with('menus', $menus);
    }
    public function updateMenuOrder(Request $request)
    {
        foreach ($request->order as $item) {

            Menu::where('id', $item['id'])->update(['sequence_id' => $item['sequence_id']]);
        }

        try {
            app(\App\Services\ExportHome::class)->run();
        } catch (\Throwable $e) {
            \Log::error('ExportHome failed', ['error' => $e->getMessage()]);
        }

        return response()->json(['success' => true]);
    }
}
