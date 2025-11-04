<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreFileRequest;
use App\Models\Category;
use App\Models\File;
use App\Models\Blog;
use App\Models\State;
use App\Models\SubCategory;
use App\Models\Ads;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        // $categories = Category::paginate(20);

        $perPage = $request->input('perPage', 20);
        $categories = Category::paginate($perPage);

        $categories->setPath(asset('/categories'));

        // Build query parameters for pagination links
        $queryParams = $request->except('page');
        if ($perPage != 20) {
            $queryParams['perPage'] = $perPage;
        }

        // Set the pagination path with query parameters
        $categories->setPath(asset('/categories') . '?' . http_build_query($queryParams));

        return view('admin/categoryList')->with(['categories' => $categories,'perPage' => $perPage]);
    }

    public function addCategory(Request $request)
    {
        $categories = Category::get()->all();
       // $file = File::get()->all();
        $role = call_user_func(config('global.getuser_role'));
        return view('admin/addCategory')->with('data' , ['categories'=> $categories]);
    }
    public function categoryAdd(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'eng_name' => 'required|string'
        ]);
        $url = $this->clean($request->eng_name);
        $url = strtolower(str_replace(' ', '-',trim($url)));
        $status  = 0;
        if($request->home_page_status) {
            $status =1;
        }
        Category::create([
            'name' => $request->name,
            'eng_name' => $request->eng_name,
            'site_url' => $url,
            'image_name' => 0,
            'home_page_status' => $status,
            'category_id' => $request->category,
        ]);
        return redirect('/categories');
    }
    public function editCategory($id, Request $request)
    {
        
        $singleCate = Category::get()->where('id', $id)->first();
        $categories = Category::get()->all();
        // $file = File::get()->all();
        $file = File::orderBy('id', 'DESC');
        return view('admin/editCategory')->with('data', ['categories' =>$categories, 'singleCate' => $singleCate, 'files' => $file]);
    }
    public function categoryEdit($id, Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'eng_name' => 'required|string'
        ]);
        $url = $this->clean($request->eng_name);
        $url = strtolower(str_replace(' ', '-',trim($url)));
        $status  = 0;
        if($request->home_page_status) {
            $status =1;
        }
        Category::where('id', $id)->update([
            'name' => $request->name,
            'eng_name' => $request->eng_name,
            'site_url' => $url,
            // 'image_name' => $request->file,
            'image_name' => 0,
            'home_page_status' => $status,
            'category_id' => $request->category,
        ]);
        return redirect('/categories');
    }
    public function clean($string) {
        $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
        $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
        $string = trim($string);
        return preg_replace('/-+/', ' ', $string); // Replaces multiple hyphens with single one.
     }
    public function del($id, Request $request) 
    {
        ?>
        <script>
            if (confirm('Are You Sure You want Delete Category')) {
                window.location.href =  '<?php echo asset('/categories/del').'/'.$id; ?>'
            } else {
                window.location.href =  '<?php echo asset('/categories'); ?>'
            }
        </script>
        <?php
    }
    public function deleteCategory($id, Request $request)
    {
        Category::where('id', $id)->delete();
        return redirect('/categories');
    }

    public function updateStatus(Request $request)
    {
        $category = Category::find($request->category_id);

        if (!$category) {
            return response()->json(['success' => false, 'message' => 'Invalid category ID']);
        }

        $category->home_page_status = $request->active_status ? 1 : 0;
        $category->save();

        return response()->json(['success' => true]);
    }

    public function categoryLoadMore(Request $request, $name)
    {
        try {
            // Log::info('categoryLoadMore called', [
            //     'name' => $name,
            //     'state' => $request->input('state', ""),
            //     'subcat' => $request->input('subcat', ""),
            //     'offset' => $request->input('offset', 0)
            // ]);

            $state = $request->input('state', "");
            $subcat = $request->input('subcat', "");
            $offset = (int) $request->input('offset', 0);
            $limit = 10;

            // Get category
            $category = Category::where('site_url', $name)->firstOrFail();

            // State filter
            $stateid = 0;
            if ($state != "") {
                $stateObj = State::where('site_url', $state)->first();
                $stateid = $stateObj->id ?? 0;
            }

            // Subcat filter
            $subcatid = 0;
            if ($subcat != "") {
                $subcatObj = SubCategory::where('site_url', $subcat)->first();
                $subcatid = $subcatObj->id ?? 0;
            }

            $categoryAds = Ads::where('page_type', 'category')->get()->keyBy('location');

            // Main query (excluding top 5)
            $topBlogIds = Blog::where('status', '1')
                ->where(function ($query) use ($category) {
                    $query->where('categories_ids', $category->id)
                        ->orWhereRaw("FIND_IN_SET(?, mult_cat)", [$category->id]);
                })
                ->orderBy('created_at', 'DESC')
                ->limit(5)
                ->pluck('id')
                ->toArray();

            $blogsQuery = Blog::where('status', '1')
                ->whereNotIn('id', $topBlogIds)
                ->where(function ($query) use ($category) {
                    $query->where('categories_ids', $category->id)
                        ->orWhereRaw("FIND_IN_SET(?, mult_cat)", [$category->id]);
                })
                ->orderBy('created_at', 'DESC')
                ->with('images')
                ->skip($offset)
                ->take($limit);

            if ($stateid > 0) $blogsQuery->where('state_ids', $stateid);
            if ($subcatid > 0) $blogsQuery->where('sub_category_id', $subcatid);

            $blogs = $blogsQuery->get();

            return response()->json([
                'blogs' => view('components.category.blog-list', [
                    'blogs' => $blogs,
                    'categoryAds' => $categoryAds ?? [],
                    'category' => $category
                ])->render(),
                'count' => $blogs->count()
            ]);

        } catch (\Exception $e) {
            Log::error('categoryLoadMore error: '.$e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
