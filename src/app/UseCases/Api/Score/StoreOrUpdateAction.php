<?php

namespace App\UseCases\Api\Score;

use App\Models\Score;
use App\Models\User;
use App\Traits\Aes;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;

class StoreOrUpdateAction
{
    use Aes;

    /**
     * @var MessageBag
     */
    private $message_bag;

    /**
     * constructor
     */
    public function __construct()
    {
        $this->message_bag = new MessageBag;
    }

    /**
     * @param  array<mixed>  $request_body
     * @return array
     */
    public function __invoke(array $request_body): array
    {
        // リクエストボディにある暗号化された文字列を取得する
        $encrypted_message = $this->getEncryptedData($request_body);
        logger()->info(sprintf('encrypted_message: %s', $encrypted_message));
        if ($this->message_bag->isNotEmpty()) {
            return ['', $this->message_bag];
        }

        // 暗号化された文字列を復号化してデータの配列を取得する
        $request_all = $this->decrypt($encrypted_message);

        // バリデーション実行
        $validator = Validator::make($request_all, [
            'user_id' => 'required|integer',
            'score' => 'required|integer',
        ]);

        if ($validator->fails()) {
            $this->message_bag->add('error', 'invalid request.');
            $failed = $validator->failed();
            logger()->warning('invalid request.', $validator->getMessageBag()->all());

            return ['', $this->message_bag];
        }

        // バリデート済みのデータを取得
        $validated = $validator->validated();
        $user_id = $validated['user_id'];
        $current_score = (int) $validated['score'];

        logger()->info('validated data', compact('user_id', 'current_score'));

        // データの作成or更新
        /** @var User $user */
        $user = User::query()
                ->where('id', $user_id)
                ->first();

        if ($user === null) {
            logger()->error('this user is not exsists.', compact('user_id'));
            $this->message_bag->add('error', 'no user id');

            return ['', $this->message_bag];
        }

        $score = $user->score;

        if ($score === null) {
            // createする
            $insert_score = new Score();
            $best_score = 0;
            $insert_score->fill(['user_id' => $user_id, 'score' => $current_score])->save();
            $is_new_record = true;
        } else {
            // updateする
            /** @var Score $score */
            $best_score = $score->score;
            $is_new_record = $current_score > $best_score;
            if ($is_new_record) {
                $score->fill(['score' => $best_score])->save();
            }
        }

        // レスポンスデータを作って暗号化して返す
        $response_data = compact('is_new_record', 'current_score', 'best_score');
        $encrypted = $this->encrypt($response_data);

        return [$encrypted, $this->message_bag];
    }

    /**
     * @param  array  $request_body
     * @return string
     */
    private function getEncryptedData(array $request_body): string
    {
        if (isset($request_body['data']) === false) {
            logger()->warning('ボディにdataがありません。');
            $this->message_bag->add('error', 'invalid request.');

            return '';
        }

        $encrypted_data = $request_body['data'];

        if (is_string($encrypted_data) === false) {
            logger()->warning('ボディにstring以外の型データがあります');
            $this->message_bag->add('error', 'invalid request.');

            return '';
        }

        return $encrypted_data;
    }
}
