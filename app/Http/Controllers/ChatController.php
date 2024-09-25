<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use LucianoTonet\GroqLaravel\Facades\Groq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use LucianoTonet\GroqPHP\GroqException;

class ChatController extends Controller
{
    protected $openAIKey;
    protected $openAIEndpoint;

    public function __construct()
    {
        $this->openAIKey = env('OPENAI_API_KEY');
        $this->openAIEndpoint = 'https://api.openai.com/v1/completions';
    }

    public function chat(Request $request) {
        // $client = new Client();

        // $response = $client->post($this->openAIEndpoint, [
        //     'headers' => [
        //         'Content-Type' => 'application/json',
        //         'Authorization' => 'Bearer ' . $this->openAIKey,
        //     ],
        //     'json' => [
        //         'model' => 'text-davinci-003',
        //         'prompt' => $request->input('prompt'),
        //         'max_tokens' => 150,
        //         'temperature' => 0.7,
        //         'stop' => ['\n']
        //     ],
        // ]);

        // return $response->getBody()->getContents();
        try {
            $response = Groq::chat()->completions()->create([
                'model' => 'llama-3.1-8b-instant',
                'messages' => [
                    ['role' => 'user', 'content' => $request->input('prompt')],
                ],
            ]);
        } catch(GroqException $e) {
            Log::error('Error in Groq API: ' . $e->getMessage());
            abort(500, 'Error in processing your chat request');
        }
        return $response;
    }
}
