<?php

namespace App\Http\Controllers\Admin;

use App\Criteria\InformationCriteria;
use App\Criteria\PurposeCriteria;
use App\Enum\InformationImage;
use App\Http\Requests\InformationRequest;
use App\Repositories\InformationRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class InformationController extends Controller
{
    protected $repos;

    public function __construct(InformationRepository $repos)
    {
        $this->middleware('auth:admin');
        $this->repos = $repos;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $informations = \App::make('information.search')->run((new InformationCriteria($request->query)));

        return view('admin.information.index', [
            'informations' => $informations,
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function indexPost(Request $request)
    {
        if (! $request->request->has('ids')) {
            \Flash::success('対応する物件がありません。');
            return redirect()->route('admin.information.index');
            exit();
        }


        foreach ($request->request->get('ids') as $id) {
            $row = \App::make('information.get_one')->run($id);
            if (empty($row)) continue;
            $this->repos->delete($id);

        }

        \Flash::success('削除しました。');
        return redirect()->route('admin.information.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.information.edit');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(InformationRequest $request)
    {
        $information = $this->repos->create($request->all());

        /**
         * FileUpload
         */
        if ($request->file('pdf')) {
            $data = [];
            $data['pdf'] = \App::make('pdf.upload')->run($request->file('pdf'));
            $this->repos->update($data, $information->id);
        }

        if ($request->file('main_image')) {
            $data = [];
            $data['main_image'] = \App::make('information.upload')->run($request->file('main_image'));
            $this->repos->update($data, $information->id);
        }

        \Flash::success('登録しました。登録番号：' . $information->id);
        return redirect()->route('admin.information.index');
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
    public function edit(int $id)
    {
        $row = \App::make('information.get_one')->run($id);

        if (empty($row)) {
            \Flash::success('対応する物件がありません。');
            return redirect()->route('admin.information.index');
            exit();
        }

        return view('admin.information.edit',
            [
                'row' => $row,
            ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(InformationRequest $request, int $id)
    {
        $information = $this->repos->update($request->all(), $id);

        /**
         * FileUpload
         */
        if ($request->file('pdf')) {
            $data['pdf'] = \App::make('pdf.upload')->run($request->file('pdf'));
            $this->repos->update($data, $information->id);
        }

        if (!empty($request->request->get('deleteImage'))) {
            $data = \App::make('information.bulk_delete')->run($request->request->get('deleteImage'), InformationImage::values(), $information);
            $this->repos->update($data, $information->id);
        }

        if ($request->file('main_image')) {
            $data = [];
            $data['main_image'] = \App::make('information.upload')->run($request->file('main_image'));
            $this->repos->update($data, $information->id);
        }

        \Flash::success('更新しました。');
        return redirect()->route('admin.information.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $this->repos->delete($id);

        \Flash::success('削除しました。');
        return redirect()->route('admin.information.index');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function publish(int $id)
    {
        $row = \App::make('information.get_one')->run($id);

        if (empty($row)) {
            \Flash::success('対応する物件がありません。');
            return redirect()->route('admin.information.index');
            exit();
        }

        $data = ['status' => 1, 'published_at' => date('Y-m-d H:i:s')];
        $this->repos->update($data, $id);

        \Flash::success('公開しました。');
        return redirect()->route('admin.information.index');
    }

    /**
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function copy(int $id)
    {
        $row = \App::make('information.get_one')->run($id);

        if (empty($row)) {
            \Flash::success('対応する物件がありません。');
            return redirect()->route('admin.information.index');
            exit();
        }

        return view('admin.information.edit',
            [
                'row' => $row,
            ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function copyPost(int $id, InformationRequest $request)
    {
        $row = \App::make('information.get_one')->run($id);

        if (empty($row)) {
            \Flash::success('対応する物件がありません。');
            return redirect()->route('admin.information.index');
            exit();
        }

        /**
         * 画像のコピー
         */
        foreach(InformationImage::values() as $enum) {
            if (!empty($row->toArray()[$enum->value()])){
                $filename = \App::make('information.copy')->run($row->toArray()[$enum->value()]);
                $request->merge([$enum->value() => $filename]);
            }
        }

        $information = $this->repos->create($request->all());

        /**
         * FileUpload
         */
        if ($request->file('pdf')) {
            $data = [];
            $data['pdf'] = \App::make('pdf.upload')->run($request->file('pdf'));
            $this->repos->update($data, $information->id);
        }

        if ($request->file('main_image')) {
            $data = [];
            $data['main_image'] = \App::make('information.upload')->run($request->file('main_image'));
            $this->repos->update($data, $information->id);
        }

        \Flash::success('コピーしました。登録番号：' . $information->id);
        return redirect()->route('admin.information.index');
    }
}
