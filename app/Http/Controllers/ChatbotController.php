<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ChatbotController extends Controller
{
    /**
     * Xử lý tin nhắn gửi tới Chatbot Trợ lý ảo
     */
    public function reply(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $userMessage = trim($request->message);
        $clean = mb_strtolower($userMessage, 'UTF-8');

        // Phân tích và tạo phản hồi
        $response = $this->generateBotResponse($clean, $userMessage);

        return response()->json($response);
    }

    /**
     * Bộ máy xử lý ngôn ngữ tự nhiên & truy vấn dữ liệu sản phẩm
     */
    protected function generateBotResponse(string $clean, string $original): array
    {
        // 1. Chào hỏi
        if (preg_match('/^(chào|xin chào|hello|hi|hey|alo|ê|bạn ơi|chao ban|hi bot)/u', $clean) && mb_strlen($clean) < 25) {
            return [
                'reply' => "Xin chào bạn! 👋 Tôi là **Trợ lý ảo Chợ Tốt**. Tôi có thể giúp bạn:\n• 🔍 Tìm kiếm món đồ cũ giá rẻ, chất lượng\n• 📝 Hướng dẫn đăng tin bán đồ nhanh chóng\n• 🛡️ Chia sẻ mẹo giao dịch an toàn, tránh lừa đảo\n\nBạn cần tìm món đồ nào hoặc cần hỗ trợ gì hôm nay?",
                'suggestions' => [
                    '🔍 Tìm iPhone giá rẻ',
                    '🛵 Tìm Xe máy',
                    '📝 Cách đăng tin',
                    '🛡️ Mẹo mua an toàn',
                ],
                'products' => [],
            ];
        }

        // 2. Hỏi bot là ai
        if (preg_match('/(bạn là ai|mày là ai|giới thiệu|bot là gì|trợ lý gì)/u', $clean)) {
            return [
                'reply' => "Tôi là **Trợ Lý Ảo Thông Minh** của website Chợ Tốt Đồ Cũ 🤖.\nNhiệm vụ của tôi là hỗ trợ bạn 24/7 tìm kiếm sản phẩm đang đăng bán, hướng dẫn sử dụng và giải đáp mọi thắc mắc khi giao dịch trên website.",
                'suggestions' => [
                    '🔍 Tìm sản phẩm',
                    '📦 Tin đăng mới nhất',
                    '📝 Hướng dẫn đăng tin',
                ],
                'products' => [],
            ];
        }

        // 3. Mẹo an toàn / Tránh lừa đảo / Đặt cọc
        if (preg_match('/(lừa đảo|an toàn|cọc|đặt cọc|chuyển khoản|bị lừa|uy tín|mẹo mua)/u', $clean)) {
            return [
                'reply' => "🛡️ **MẸO GIAO DỊCH AN TOÀN TRÊN CHỢ TỐT:**\n\n1. ❌ **Tuyệt đối KHÔNG chuyển khoản đặt cọc** trước khi gặp mặt xem hàng thực tế.\n2. 📍 **Gặp gỡ nơi công cộng**, đông người qua lại vào ban ngày (quán cà phê, trung tâm thương mại).\n3. 🔍 **Kiểm tra kỹ tình trạng sản phẩm**: Kiểm tra tính năng, phụ kiện, hóa đơn hoặc giấy tờ xe chính chủ.\n4. ⚠️ **Cẩn trọng với giá quá rẻ**: Món đồ có giá rẻ bất thường so với thị trường thường tiềm ẩn rủi ro hỏng hóc hoặc hàng nhái.\n5. 🚨 Nếu phát hiện tin đăng nghi vấn gian lận, hãy liên hệ ngay với Ban quản trị để khóa tài khoản.",
                'suggestions' => [
                    '🔍 Tìm sản phẩm giá rẻ',
                    '📞 Liên hệ hỗ trợ',
                ],
                'products' => [],
            ];
        }

        // 4. Hướng dẫn đăng tin bán đồ
        if (preg_match('/(cách đăng tin|hướng dẫn đăng|làm sao đăng|bán đồ|muốn bán|tạo tin|đăng bài|đăng tin)/u', $clean)) {
            return [
                'reply' => "📝 **CÁCH ĐĂNG TIN BÁN ĐỒ NHANH CHÓNG:**\n\n1. **Bước 1**: Đăng nhập tài khoản trên website (hoặc đăng ký nếu chưa có).\n2. **Bước 2**: Nhấp vào nút cam **[+ ĐĂNG TIN]** ở góc trên cùng bên phải màn hình.\n3. **Bước 3**: Chọn đúng Danh mục sản phẩm (Điện thoại, Xe máy, Đồ gia dụng,...).\n4. **Bước 4**: Điền tiêu đề, giá bán mong muốn, số điện thoại liên hệ và mô tả chi tiết tình trạng món đồ.\n5. **Bước 5**: Tải lên từ 1-5 hình ảnh thực tế rõ nét của món đồ.\n6. **Bước 6**: Bấm **\"Đăng tin ngay\"** — Tin của bạn sẽ được hiển thị ngay lập tức!",
                'suggestions' => [
                    'Đăng tin ngay',
                    '🛡️ Mẹo bán đồ nhanh',
                    '📦 Xem tin đã đăng',
                ],
                'products' => [],
            ];
        }

        // 5. Quản lý tài khoản, mật khẩu, thông tin cá nhân
        if (preg_match('/(tài khoản|đổi mật khẩu|quên mật khẩu|hồ sơ|avatar|thông tin cá nhân)/u', $clean)) {
            return [
                'reply' => "👤 **QUẢN LÝ TÀI KHOẢN:**\n\n• Để cập nhật tên, địa chỉ hoặc ảnh đại diện: Nhấp vào biểu tượng ảnh đại diện ở góc phải trên menu, chọn **\"Cài đặt thông tin\"**.\n• Để xem lại các tin bạn đã đăng: Chọn **\"Quản lý tin của tôi\"**.\n• Để xem danh sách các món đồ bạn đã bấm thích: Chọn **\"Tin đã lưu\"**.",
                'suggestions' => [
                    'Quản lý tin của tôi',
                    'Tin đã lưu',
                ],
                'products' => [],
            ];
        }

        // 6. Liên hệ ban quản trị / Hotline
        if (preg_match('/(liên hệ|hotline|số điện thoại admin|tổng đài|gặp quản trị|khiếu nại|hỗ trợ)/u', $clean)) {
            return [
                'reply' => "☎️ **THÔNG TIN LIÊN HỆ & HỖ TRỢ:**\n\n• **Email hỗ trợ**: hotro@chototdocu.vn\n• **Hotline**: 1900 1234 (8h00 - 21h00 các ngày trong tuần)\n• **Địa chỉ**: Tòa nhà Công Nghệ, Quận 1, TP. Hồ Chí Minh\n\nChúng tôi luôn sẵn sàng hỗ trợ bạn kiểm tra giao dịch và giải quyết khiếu nại!",
                'suggestions' => [
                    '🛡️ Mẹo mua an toàn',
                    '🔍 Tìm kiếm sản phẩm',
                ],
                'products' => [],
            ];
        }

        // 7. Tin mới nhất / Hot nhất
        if (preg_match('/(mới nhất|tin mới|hàng mới|sản phẩm mới|có gì mới|hot)/u', $clean)) {
            $latestProducts = Product::active()
                ->with(['category', 'images'])
                ->latest()
                ->take(4)
                ->get();

            return [
                'reply' => "Dưới đây là các **tin đăng mới nhất** vừa được đăng bán trên Chợ Tốt hôm nay:",
                'products' => $this->formatProducts($latestProducts),
                'suggestions' => [
                    '📱 Điện thoại',
                    '🛵 Xe máy',
                    '💻 Laptop & Đồ điện tử',
                ],
            ];
        }

        // 8. Ý định tìm kiếm sản phẩm (theo từ khóa hoặc danh mục)
        $searchResult = $this->searchProductsFromQuery($clean);
        if ($searchResult !== null) {
            return $searchResult;
        }

        // 9. Tích hợp AI Gemini nếu có cấu hình GEMINI_API_KEY
        $geminiKey = env('GEMINI_API_KEY');
        if (!empty($geminiKey)) {
            $aiReply = $this->callGeminiApi($original, $geminiKey);
            if ($aiReply) {
                return [
                    'reply' => $aiReply,
                    'products' => [],
                    'suggestions' => [
                        '🔍 Tìm sản phẩm',
                        '📝 Cách đăng tin',
                        '🛡️ Mẹo mua an toàn',
                    ],
                ];
            }
        }

        // 10. Phản hồi mặc định thân thiện
        return [
            'reply' => "Xin lỗi, tôi chưa hiểu rõ yêu cầu: *\"{$original}\"*. 😅\n\nBạn có thể thử:\n• Nhập tên món đồ muốn tìm (Ví dụ: *\"tìm iPhone 13\"*, *\"xe wave\"*, *\"tủ lạnh cũ\"*)\n• Hoặc bấm vào một trong các gợi ý nhanh bên dưới:",
            'products' => [],
            'suggestions' => [
                '🔍 Tìm iPhone',
                '🛵 Tìm Xe máy',
                '📝 Hướng dẫn đăng tin',
                '🛡️ Mẹo tránh lừa đảo',
                '📦 Tin đăng mới nhất',
            ],
        ];
    }

    /**
     * Tìm kiếm sản phẩm thông minh dựa trên câu hỏi của người dùng
     */
    protected function searchProductsFromQuery(string $clean): ?array
    {
        // Trích xuất từ khóa tìm kiếm
        $keyword = null;
        $maxPrice = null;

        // Bóc tách tầm giá (ví dụ: dưới 5 triệu, dưới 10tr, tầm 3 triệu)
        if (preg_match('/(dưới|tầm|khoảng|giá)\s+(\d+)\s*(triệu|tr)/u', $clean, $matches)) {
            $maxPrice = (float) $matches[2] * 1000000;
        }

        // Bỏ các từ đệm tiếng Việt
        $fillers = [
            'tìm giúp tôi', 'tìm cho tôi', 'tìm hộ tôi', 'tìm kiếm', 'tìm mua', 'cần tìm', 'cần mua',
            'tìm', 'kiếm', 'mua', 'cho tôi xem', 'cho mình xem', 'cho xem', 'giúp tôi', 'hộ tôi', 'cho tôi', 'cho mình',
            'có bán không', 'có không', 'bán không', 'ở đâu', 'nhé', 'với', 'ạ', 'ơi', 'giá bao nhiêu'
        ];
        $cleanSearch = $clean;
        foreach ($fillers as $f) {
            $cleanSearch = str_replace($f, ' ', $cleanSearch);
        }
        // Bỏ phần giá nếu có trong chuỗi
        $cleanSearch = preg_replace('/(dưới|trên|tầm|khoảng|giá)\s+\d+\s*(triệu|tr|k|đ|dong)?/u', '', $cleanSearch);
        $cleanSearch = trim(preg_replace('/\s+/', ' ', $cleanSearch));

        // Các từ khóa sản phẩm phổ biến
        $commonKeywords = [
            'iphone', 'samsung', 'oppo', 'xiaomi', 'điện thoại', 'dien thoai',
            'xe máy', 'xe may', 'honda', 'yamaha', 'wave', 'vision', 'sh', 'sirius', 'air blade',
            'laptop', 'macbook', 'dell', 'asus', 'hp', 'máy tính', 'pc',
            'tủ lạnh', 'tu lanh', 'máy giặt', 'may giat', 'tivi', 'nồi cơm',
            'bàn ghế', 'ban ghe', 'nội thất', 'sofa', 'giường',
            'chó', 'mèo', 'thú cưng', 'quần áo', 'giày', 'đồng hồ', 'tai nghe'
        ];

        $matchedKeyword = null;
        if (!empty($cleanSearch) && mb_strlen($cleanSearch) >= 2) {
            $matchedKeyword = $cleanSearch;
        } else {
            foreach ($commonKeywords as $kw) {
                if (mb_strpos($clean, $kw) !== false) {
                    $matchedKeyword = $kw;
                    break;
                }
            }
        }

        if (!$matchedKeyword) {
            return null;
        }

        $query = Product::active()->with(['category', 'images']);

        // Tách các từ đơn lẻ để tìm kiếm linh hoạt (ví dụ "iphone 13")
        $tokens = array_filter(explode(' ', $matchedKeyword), fn($t) => mb_strlen($t) >= 2);
        if (!empty($tokens)) {
            $query->where(function ($q) use ($tokens, $matchedKeyword) {
                $q->where('title', 'like', "%{$matchedKeyword}%")
                  ->orWhere(function ($sub) use ($tokens) {
                      foreach ($tokens as $token) {
                          $sub->where('title', 'like', "%{$token}%");
                      }
                  });
            });
        } else {
            $query->where(function ($q) use ($matchedKeyword) {
                $q->where('title', 'like', "%{$matchedKeyword}%")
                  ->orWhere('description', 'like', "%{$matchedKeyword}%");
            });
        }

        if ($maxPrice) {
            $query->where('price', '<=', $maxPrice);
        }

        $products = $query->latest()->take(4)->get();

        if ($products->isNotEmpty()) {
            $priceText = $maxPrice ? ' (giá dưới ' . number_format($maxPrice, 0, ',', '.') . ' đ)' : '';
            return [
                'reply' => "🎉 Tìm thấy **{$products->count()} sản phẩm** phù hợp với từ khóa **\"{$matchedKeyword}\"**{$priceText}:",
                'products' => $this->formatProducts($products),
                'suggestions' => [
                    '📦 Xem tin đăng mới nhất',
                    '📝 Hướng dẫn đăng tin',
                    '🛡️ Mẹo mua an toàn',
                ],
            ];
        }

        // Nếu có từ khóa nhưng không tìm thấy sản phẩm
        return [
            'reply' => "Rất tiếc, hiện tại chưa có tin đăng nào phù hợp với từ khóa **\"{$matchedKeyword}\"**" . ($maxPrice ? ' trong tầm giá này' : '') . ".\n\nBạn có thể thử tìm kiếm với từ khóa khác hoặc xem qua các sản phẩm nổi bật mới nhất nhé!",
            'products' => [],
            'suggestions' => [
                '📦 Xem tin mới nhất',
                '📱 Tìm iPhone',
                '🛵 Tìm Xe máy',
            ],
        ];
    }

    /**
     * Định dạng sản phẩm trả về cho widget chatbot
     */
    protected function formatProducts($products): array
    {
        return $products->map(function ($p) {
            return [
                'id' => $p->id,
                'title' => $p->title,
                'price' => $p->formatted_price,
                'image' => $p->display_image,
                'province' => $p->province ?? 'Toàn quốc',
                'url' => route('products.show', $p->id),
            ];
        })->toArray();
    }

    /**
     * Gọi Gemini API nếu có API Key
     */
    protected function callGeminiApi(string $prompt, string $apiKey): ?string
    {
        try {
            $systemInstruction = "Bạn là trợ lý ảo AI thông minh và thân thiện của website 'Chợ Tốt Đồ Cũ'. Hãy trả lời bằng tiếng Việt ngắn gọn, súc tích, nhiệt tình, tập trung vào việc tư vấn mua bán đồ cũ an toàn và hiệu quả.";

            $response = Http::timeout(5)->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key={$apiKey}", [
                'contents' => [
                    [
                        'role' => 'user',
                        'parts' => [
                            ['text' => "{$systemInstruction}\n\nNgười dùng hỏi: {$prompt}"]
                        ]
                    ]
                ]
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['candidates'][0]['content']['parts'][0]['text'] ?? null;
            }
        } catch (\Throwable $e) {
            // Im lặng xử lý fallback
        }

        return null;
    }
}
