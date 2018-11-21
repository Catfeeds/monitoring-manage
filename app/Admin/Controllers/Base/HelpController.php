<?php

namespace app\Admin\Controllers\Base;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Help;
use Tanmo\Search\Facades\Search;
use Tanmo\Search\Query\Searcher;

/**
 * @module 帮助中心
 *
 * Class HelpController
 * @package app\Admin\Controllers\Campus
 */
class HelpController extends Controller
{
    /**
     * @permission 问答列表
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View\
     */
	public function index()
    {

        $searcher = Search::build(function (Searcher $searcher) {
            $searcher->like('title');
            $searcher->equal('scope');
        });
        $header = '帮助中心';
        $status = request()->get('status');
        $helps = (new Help())->search($searcher)->FilterStatus($status)->paginate(10);
        return view('admin::base.helps-helps',compact('helps','header'));
    }

    /**
     * @permission 问答添加
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $help = new Help();
        $help->title = $request->get('title');
        $help->content = $request->get('content');
        $help->status = $request->get('status');
        $help->scope = $request->get('scope');

        $help->save();
        return redirect()->route('admin::helps.index');
    }

    /**
     * @permission 问答修改
     *
     * @param Request $request
     * @param Help $help
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request,Help $help)
    {
        $help->title = $request->get('title');
        $help->content = $request->get('content');
        $help->status = $request->get('status');
        $help->scope = $request->get('scope');

        $help->save();
        return response()->json(['status' => 1, 'message' => '修改成功']);
    }

    /**
     * @permission 问答删除
     *
     * @param Help $help
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Help $help)
    {
        $help->delete();
        return response()->json(['status' => 1, 'message' => '成功']);
    }
}