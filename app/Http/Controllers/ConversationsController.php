<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMessageRequest;
use App\Models\User;
use App\Repository\ConversationRepository;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConversationsController extends Controller
{
    private $r;

    /**
     * @var AuthManager
     */

    private $auth;

    public function __construct(ConversationRepository $conversationRepository, AuthManager $auth)
    {
        $this->middleware('auth');
        $this->r = $conversationRepository;
        $this->auth = $auth;
    }

    public function index()
    {
        return view('conversations.index', [
            'users' => $this->r->getConversations($this->auth->user()->id),
            'unread' => $this->r->unreadCount($this->auth->user()->id)
        ]);
    }

    public function show(User $user)
    {
        $me = $this->auth->user();
        $messages = $this->r->getMessageFor($me->id, $user->id)->paginate(50);
        $unread = $this->r->unreadCount($this->auth->user()->id);

        if (isset($unread[$user->id])) 
        {
            $this->r->readAllFrom($user->id, $me->id);
            unset($unread[$user->id]);
        }

        return view('conversations.show', [
            'users' => $this->r->getConversations($this->auth->user()->id),
            'user' => $user,
            'messages' => $messages,
            'unread' => $unread
        ]);
    }

    public function store(User $user, StoreMessageRequest $request)
    {
        $this->r->createMessage(
            $request->get('content'),
            $this->auth->user()->id,
            $user->id
        );

        return redirect(route('conversations.show', ['user' => $user->id]));
    }
}
