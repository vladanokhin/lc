<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use App\Models\LeadCollectorMessagesModel;

class LeadCollectorMessages extends Controller
{
    /**
     * Messages flow
     *
     * @param Request $request
     * @return Application|Factory|View
     */
    public function index(Request $request)
    {
        return view('app.messages.message-flow', [
            'messageFlow' => LeadCollectorMessagesModel::orderBy('id', 'desc')->take(40)->get()->toArray(),
        ]);
    }

    /**
     * @param Request $request
     * @param string|null $id
     * @return string
     */
    public function show(Request $request, string $id = null): string
    {
        $result = LeadCollectorMessagesModel::where('id', $id)->get();

        return json_encode($result);
    }

    public function commitMessage($data): void
    {
        $message = new LeadCollectorMessagesModel();
        $message->title = $data['title'];
        $message->content = $data['content'];
        $message->save();
    }
}
