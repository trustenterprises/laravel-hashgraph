<?php

namespace Trustenterprises\LaravelHashgraph\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Trustenterprises\LaravelHashgraph\Events\ConsensusMessageWasReceived;
use Trustenterprises\LaravelHashgraph\Models\HashgraphConsensusMessage;
use Trustenterprises\LaravelHashgraph\Models\HashgraphTopic;
use Trustenterprises\LaravelHashgraph\Utilities\Hmac;

class LaravelHashgraphWebhookController extends Controller
{
    public function __invoke(Request $request)
    {
        // Check that the signature exists
        $signature = $request->header('x-signature');
        abort_unless(! ! $signature, Response::HTTP_BAD_REQUEST, 'Header X-Signature is required');

        // Check the signature is valid
        $hash = Hmac::generate($request->getContent());
        abort_unless($signature === $hash, Response::HTTP_BAD_REQUEST, 'Signature does not match payload');

        // The hashgraph consensus payload
        $data = (object) json_decode($request->getContent());

        // This needs to fail if topic does not exist.
        $topic = HashgraphTopic::fromTopic($data->topic_id)->first();

        abort_unless($topic, Response::HTTP_BAD_REQUEST, "Unable to store consensus message, topic does not exist");

        // Store the hashgraph consensus message
        $message = HashgraphConsensusMessage::create([
            'reference' => $data->reference ?? null,
            'explorer_url' => $data->explorer_url ?? null,
            'topic_id' => $data->topic_id,
            'transaction_id' => $data->transaction_id,
            'consensus_timestamp_seconds' => $data->consensus_timestamp->nanos,
            'consensus_timestamp_nanos' => $data->consensus_timestamp->seconds,
        ]);

        event(new ConsensusMessageWasReceived($message));
    }
}
