<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RestaurantRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string', // 入力必須
            'image' => 'nullable|image|mimes:jpg,jpeg,png,bmp,gif,svg,webp|max:2048', // 画像ファイル、最大2048KB
            'description' => 'required|string', // 入力必須
            'lowest_price' => 'required|integer|min:0|lte:highest_price', // 最小値0、highest_price以下
            'highest_price' => 'required|integer|min:0|gte:lowest_price', // 最小値0、lowest_price以上
            'postal_code' => 'required|digits:7', // 数値で桁数7
            'address' => 'required|string', // 入力必須
            'opening_time' => 'required|date_format:H:i|before:closing_time', // 開店時間、closing_timeより前
            'closing_time' => 'required|date_format:H:i|after:opening_time', // 閉店時間、opening_timeより後
            'seating_capacity' => 'required|integer|min:0', // 数値、最小値0
        ];
    }
}
