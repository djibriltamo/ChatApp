<?php

namespace App\Repository;

use App\Models\User;
use App\Models\Message;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

use function Laravel\Prompts\select;

class ConversationRepository {

    private $user;
    /**
     * @var Message
     */

    private $message;

    public function __construct(User $user,Message $message)
    {
        $this->user = $user;
        $this->message = $message;
    }

    public function getConversations(int $userId)
    {
        $conversations = $this->user->newQuery()
        ->select('name', 'id')
        ->where('id', '!=', $userId)
        ->get();

        return $conversations;
    }

    public function createMessage(string $content, int $from, int $to)
    {
        $this->message->newQuery()->create([
            'content' => $content,
            'from_id' => $from,
            'to_id' => $to,
            'created_at' => Carbon::now()
        ]);
    }

    public function getMessageFor(int $from,int $to): Builder
    {
        return $this->message->newQuery()
            ->whereRaw("((from_id = $from AND to_id) OR (from_id = $to AND to_id =$from))")
            ->orderBy('created_at', 'DESC')
            ->with([
                'from' => function($query) { return $query->select('name', 'id'); }
            ]);
    }

    /**
     * Recuperation de nombre de message non lus pourr chaque conversation
     * @param int $userId
     *@return Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Query\Builder[]|\Illuminate\Support\Collection
     */

    public function unreadCount(int $userId)
    {
        return $this->message->newQuery()
            ->where('to_id', $userId)
            ->groupBy('from_id')
            ->selectRaw('from_id, COUNT(id) as count')
            ->whereRaw('read_at IS NULL')
            ->get()
            ->pluck('count', 'from_id');
    }

    /**
     * Marque tous les messages de cet utilisateur lus
     * @param $id
     */
    public function readAllFrom(int $from, int $to)
    {
        $this->message->where('from_id', $from)->where('to_id', $to)->update(['read_at' => Carbon::now()]);
    } 

}