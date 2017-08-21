<?php

namespace App\Http\Controllers;

use Lang;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\DailyMenu;
use App\Http\Requests\DailyMenuUpdateItemRequest;
use App\Category;
use App\Food;
use App\Http\Requests\DailyMenuCreateRequest;

class DailyMenuController extends Controller
{
    /**
     * The Daily Menu implementation.
     *
     * @var DailyMenu
     */
    protected $dailyMenu;
    /**
     * The Food implementation.
     *
     * @var Food
     */
    protected $food;
    /**
     * The Category implementation.
     *
     * @var Category
     */
    protected $category;

    /**
     * Create a new controller instance.
     *
     * @param DailyMenu $dailyMenu instance of DailyMenu
     * @param Food      $food      instance of Food
     * @param Category  $category  instance of Category
     *
     * @return void
     */
    public function __construct(DailyMenu $dailyMenu, Food $food, Category $category)
    {
        $this->dailyMenu = $dailyMenu;
        $this->food = $food;
        $this->category = $category;
    }

    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request request from view
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $dailyMenus = $this->dailyMenu->select('date');
        if ($request->has('date')) {
            $dailyMenus = $dailyMenus->where('date', 'like', '%'.$request['date'].'%');
        }
        $dailyMenus = $dailyMenus->distinct()->orderBy('date', 'desc')->paginate(DailyMenu::ITEMS_PER_PAGE);
        return view('daily_menus.index', ['dailyMenus' => $dailyMenus, 'date' => $request['date']]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param \Illuminate\Http\Request $request request value
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $listCategory = $this->category->get();
        if ($request->ajax()) {
            $categoryId = $request->category_id;
            $listFood = $this->food->where('category_id', $categoryId)->paginate($this->dailyMenu->ITEMS_PER_PAGE);
            return response()->json($listFood);
        } elseif ($request->has('date')) {
            return view('daily_menus.create', ['listCategory' => $listCategory, 'date' => $request['date']]);
        }
        return view('daily_menus.create', ['listCategory' => $listCategory]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param DailyMenuCreateRequest $request request value
     *
     * @return \Illuminate\Http\Response
     */
    public function store(DailyMenuCreateRequest $request)
    {
        $date = $request['date'];
        $foodId = $request['food_id'];
        $quantity = $request['quantity'];
        /**
         * Check if food has existed in DB, if true then update new quantity,
         * If false then add this food into menu
         */
        $matchDailyMenu = array('date' => $date, 'food_id' => $foodId);
        $status = $this->dailyMenu->updateOrCreate($matchDailyMenu, ['quantity' => $quantity]);

        if ($status) {
            $request->session()->flash('message.level', 'success');
            $request->session()->flash('message.content', __('Menu was successfully added!'));
        } else {
            $request->session()->flash('message.level', 'danger');
            $request->session()->flash('message.content', __('Error'));
        }
        return redirect()->route('daily-menus.create', ['date' => $date]);
    }

    /**
     * Display the specified resource.
     *
     * @param string $date The date to get menu to show
     *
     * @return \Illuminate\Http\Response
     */
    public function show($date)
    {
        $menuOnDate = $this->dailyMenu->with('food.category')->where('date', $date)->paginate($this->dailyMenu->ITEMS_PER_PAGE);

        return view('daily_menus.show', ['menuOnDate' => $menuOnDate, 'date' => $date]);
    }

    /**
     * Update item in Menu List on Date
     *
     * @param DailyMenuUpdateItemRequest $request The request message from Ajax request
     *
     * @return Response
     */
    public function update(DailyMenuUpdateItemRequest $request)
    {
        $quantity = $request['quantity'];
        $menuId = $request['menuId'];
        $dailyMenu = $this->dailyMenu->find($menuId);
        $error = __('Has error during update menu item');
        $success = __('Update menu item success');

        if ($dailyMenu->update(['quantity' => $quantity])) {
            $result = [$dailyMenu->updated_at, 'message' => $success];
            return response()->json($result, Response::HTTP_OK);
        } else {
            return response()->json(['error' => $error], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Delete item in Menu List on Date
     *
     * @param Request $request The request message from Ajax request
     * @param string  $date    The date value to delete
     *
     * @return Response
     */
    public function destroy(Request $request, $date)
    {
        $error = __('Has error during delete this');
        $success = __('Delete this menu success');

        if ($request->ajax()) {
            $menuId = $request['menuId'];
            if ($this->dailyMenu->where('id', $menuId)->delete()) {
                return response()->json(['message' => $success], Response::HTTP_OK);
            } else {
                return response()->json(['error' => $error], Response::HTTP_NOT_FOUND);
            }
        } else {
            if ($this->dailyMenu->where('date', $date)->delete()) {
                flash($success)->success()->important();
            } else {
                flash($error)->error()->important();
            }
        }
        return redirect()->route('daily-menus.index');
    }
}
