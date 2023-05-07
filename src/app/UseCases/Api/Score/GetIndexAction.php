<?php

namespace App\UseCases\Api\Score;

use App\Models\Score;
use App\Models\User;
use App\Traits\Aes;
use Illuminate\Support\MessageBag;

class GetIndexAction
{
    use Aes;

    /**
     * @var MessageBag
     */
    private $message_bag;

    public function __construct()
    {
        $this->message_bag = new MessageBag;
    }

    /**
     * 自分のハイスコアとスコアのランキング5位までを返す
     *
     * @property int $user_id
     *
     * @return array
     */
    public function __invoke(int $user_id): array
    {
        /** @var Score $eloquent_score */
        $eloquent_score = Score::query()
                    ->where('user_id', $user_id)
                    ->first();

        if ($eloquent_score === null) {
            $this->message_bag->add('error', sprintf('no user id: %d', $user_id));

            return ['', $this->message_bag];
        }

        $my_score = $eloquent_score->score;

        $players = User::query()
             ->join('scores', static function ($join) {
                 $join->on('users.id', '=', 'scores.user_id')
                      ->orderByDesc('scores.score')
                      ->limit(5);
             })
             ->select('users.name', 'scores.score')
             ->orderByDesc('scores.score')
             ->get()
             ->toArray();

        logger()->info('ranking_users', ['users' => $players]);

        $response_data = compact('my_score', 'players');
        $encrypted = $this->encrypt($response_data);

        return [$encrypted, $this->message_bag];
    }
}
