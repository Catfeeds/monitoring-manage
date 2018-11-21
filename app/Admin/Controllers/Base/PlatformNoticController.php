<?php

namespace App\Admin\Controllers\Base;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\PlatformNotic;
use Tanmo\Search\Facades\Search;
use Tanmo\Search\Query\Searcher;
use Tanmo\Admin\Facades\Admin;

class PlatformNoticController extends Controller
{
    public function index()
    {
        $searcher = Search::build(function (Searcher $searcher) {
            $searcher->like('title');
        });
        $header = '平台消息';

        $notics =(new PlatformNotic())->search($searcher)->latest()->paginate(10);
        return view('admin::base.notics-notics',compact('notics','header'));
    }

    public function create()
    {
        $header = '新增平台消息';

        return view('admin::base.notics-create',compact('header','collectives'));
    }


    public function store(Request $request)
    {
        $notic = new PlatformNotic();
        $notic->title = $request->get('title');
        $notic->content = $request->get('content');
        $notic->admin_id = Admin::user()->id;

        $notic->save();
        return redirect()->route('admin::platNotics.index');
    }

    public function edit(PlatformNotic $notic)
    {
        $header = '编辑通知';
        return view('admin::base.platNotics-edit', compact('notic', 'header'));
    }


    public function update(Request $request,PlatformNotic $notic)
    {
        $notic->title = $request->get('title');
        $notic->content = $request->get('content');
        $notic->save();
        return redirect()->route('admin::platNotics.index');
    }

    public function destroy(PlatformNotic $notic)
    {
        $notic->delete();
        return response()->json(['status' => 1, 'message' => '成功']);
    }
}
