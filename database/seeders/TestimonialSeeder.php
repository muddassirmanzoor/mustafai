<?php

namespace Database\Seeders;

use App\Models\Admin\Testimonial;
use Illuminate\Database\Seeder;
use Faker\Generator;
use Illuminate\Container\Container;

class TestimonialSeeder extends Seeder
{

    /**
     * The current Faker instance.
     *
     * @var \Faker\Generator
     */
    protected $faker;

    /**
     * Create a new seeder instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->faker = $this->withFaker();
    }

    /**
     * Get a new Faker instance.
     *
     * @return \Faker\Generator
     */
    protected function withFaker()
    {
        return Container::getInstance()->make(Generator::class);
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Testimonial::insert(
            array(
                array(
                    'name_english' => 'Muhammad Talha',
                    'name_urdu' => 'محمد طلحہ',
                    'name_arabic' => 'محمد طلحہ',
                    'image' => 'images/testimonials-images/dontaner-1.png',
                    'message_english' => "<p>Nam fermentum, ipsum in suscipit pharetra, mi odio aliquet neque, non iaculis augue elipsum in suscipit pharetra, mi odio aliquet neque, non iaculis augue elit it et libero. Phasellus tempor faucibus faucibus. Sed eu mauris sem. Etiam et varius felis. Maecenas interdum lorem eleifend orci aliquam mollis. Aliquam non rhoncus magna</p>",
                    'message_urdu' => 'Lorem Ipsum پرنٹنگ اور ٹائپ سیٹنگ انڈسٹری کا محض ڈمی ٹیکسٹ ہے۔ Lorem Ipsum 1500 کی دہائی سے انڈسٹری کا معیاری ڈمی ٹیکسٹ رہا ہے، جب ایک نامعلوم پرنٹر نے قسم کی ایک گیلی لی اور اسے ایک قسم کے نمونے کی کتاب بنانے کے لیے گھسایا۔ یہ نہ صرف پانچ صدیوں تک زندہ رہا ہے بلکہ الیکٹرانک ٹائپ سیٹنگ میں بھی چھلانگ لگا ہوا ہے، بنیادی طور پر کوئی تبدیلی نہیں کی گئی۔ اسے 1960 کی دہائی میں Lorem Ipsum حصئوں پر مشتمل Letraset شیٹس کے اجراء کے ساتھ اور حال ہی میں Aldus PageMaker جیسے ڈیسک ٹاپ پبلشنگ سوفٹ ویئر کے ساتھ مقبول کیا گیا جس میں Lorem Ipsum کے ورژن بھی شامل ہیں۔ ',
                    'message_arabic' => 'Lorem Ipsum پرنٹنگ اور ٹائپ سیٹنگ انڈسٹری کا محض ڈمی ٹیکسٹ ہے۔ Lorem Ipsum 1500 کی دہائی سے انڈسٹری کا معیاری ڈمی ٹیکسٹ رہا ہے، جب ایک نامعلوم پرنٹر نے قسم کی ایک گیلی لی اور اسے ایک قسم کے نمونے کی کتاب بنانے کے لیے گھسایا۔ یہ نہ صرف پانچ صدیوں تک زندہ رہا ہے بلکہ الیکٹرانک ٹائپ سیٹنگ میں بھی چھلانگ لگا ہوا ہے، بنیادی طور پر کوئی تبدیلی نہیں کی گئی۔ اسے 1960 کی دہائی میں Lorem Ipsum حصئوں پر مشتمل Letraset شیٹس کے اجراء کے ساتھ اور حال ہی میں Aldus PageMaker جیسے ڈیسک ٹاپ پبلشنگ سوفٹ ویئر کے ساتھ مقبول کیا گیا جس میں Lorem Ipsum کے ورژن بھی شامل ہیں۔ ',
                    'status' => 1,
                ),
                array(
                    'name_english' => 'Muhammad Talha',
                    'name_urdu' => 'محمد طلحہ',
                    'name_arabic' => 'محمد طلحہ',
                    'image' => 'images/testimonials-images/dontaner-2.png',
                    'message_english' => "<p>Nam fermentum, ipsum in suscipit pharetra, mi odio aliquet neque, non iaculis augue elipsum in suscipit pharetra, mi odio aliquet neque, non iaculis augue elit it et libero. Phasellus tempor faucibus faucibus. Sed eu mauris sem. Etiam et varius felis. Maecenas interdum lorem eleifend orci aliquam mollis. Aliquam non rhoncus magna</p>",
                    'message_urdu' => 'Lorem Ipsum پرنٹنگ اور ٹائپ سیٹنگ انڈسٹری کا محض ڈمی ٹیکسٹ ہے۔ Lorem Ipsum 1500 کی دہائی سے انڈسٹری کا معیاری ڈمی ٹیکسٹ رہا ہے، جب ایک نامعلوم پرنٹر نے قسم کی ایک گیلی لی اور اسے ایک قسم کے نمونے کی کتاب بنانے کے لیے گھسایا۔ یہ نہ صرف پانچ صدیوں تک زندہ رہا ہے بلکہ الیکٹرانک ٹائپ سیٹنگ میں بھی چھلانگ لگا ہوا ہے، بنیادی طور پر کوئی تبدیلی نہیں کی گئی۔ اسے 1960 کی دہائی میں Lorem Ipsum حصئوں پر مشتمل Letraset شیٹس کے اجراء کے ساتھ اور حال ہی میں Aldus PageMaker جیسے ڈیسک ٹاپ پبلشنگ سوفٹ ویئر کے ساتھ مقبول کیا گیا جس میں Lorem Ipsum کے ورژن بھی شامل ہیں۔ ',
                    'message_arabic' => 'Lorem Ipsum پرنٹنگ اور ٹائپ سیٹنگ انڈسٹری کا محض ڈمی ٹیکسٹ ہے۔ Lorem Ipsum 1500 کی دہائی سے انڈسٹری کا معیاری ڈمی ٹیکسٹ رہا ہے، جب ایک نامعلوم پرنٹر نے قسم کی ایک گیلی لی اور اسے ایک قسم کے نمونے کی کتاب بنانے کے لیے گھسایا۔ یہ نہ صرف پانچ صدیوں تک زندہ رہا ہے بلکہ الیکٹرانک ٹائپ سیٹنگ میں بھی چھلانگ لگا ہوا ہے، بنیادی طور پر کوئی تبدیلی نہیں کی گئی۔ اسے 1960 کی دہائی میں Lorem Ipsum حصئوں پر مشتمل Letraset شیٹس کے اجراء کے ساتھ اور حال ہی میں Aldus PageMaker جیسے ڈیسک ٹاپ پبلشنگ سوفٹ ویئر کے ساتھ مقبول کیا گیا جس میں Lorem Ipsum کے ورژن بھی شامل ہیں۔ ',
                    'status' => 1,
                ),
                array(
                    'name_english' => 'Muhammad Talha',
                    'name_urdu' => 'محمد طلحہ',
                    'name_arabic' => 'محمد طلحہ',
                    'image' => 'images/testimonials-images/dontaner-3.png',
                    'message_english' => "<p>Nam fermentum, ipsum in suscipit pharetra, mi odio aliquet neque, non iaculis augue elipsum in suscipit pharetra, mi odio aliquet neque, non iaculis augue elit it et libero. Phasellus tempor faucibus faucibus. Sed eu mauris sem. Etiam et varius felis. Maecenas interdum lorem eleifend orci aliquam mollis. Aliquam non rhoncus magna</p>",
                    'message_urdu' => 'Lorem Ipsum پرنٹنگ اور ٹائپ سیٹنگ انڈسٹری کا محض ڈمی ٹیکسٹ ہے۔ Lorem Ipsum 1500 کی دہائی سے انڈسٹری کا معیاری ڈمی ٹیکسٹ رہا ہے، جب ایک نامعلوم پرنٹر نے قسم کی ایک گیلی لی اور اسے ایک قسم کے نمونے کی کتاب بنانے کے لیے گھسایا۔ یہ نہ صرف پانچ صدیوں تک زندہ رہا ہے بلکہ الیکٹرانک ٹائپ سیٹنگ میں بھی چھلانگ لگا ہوا ہے، بنیادی طور پر کوئی تبدیلی نہیں کی گئی۔ اسے 1960 کی دہائی میں Lorem Ipsum حصئوں پر مشتمل Letraset شیٹس کے اجراء کے ساتھ اور حال ہی میں Aldus PageMaker جیسے ڈیسک ٹاپ پبلشنگ سوفٹ ویئر کے ساتھ مقبول کیا گیا جس میں Lorem Ipsum کے ورژن بھی شامل ہیں۔ ',
                    'message_arabic' => 'Lorem Ipsum پرنٹنگ اور ٹائپ سیٹنگ انڈسٹری کا محض ڈمی ٹیکسٹ ہے۔ Lorem Ipsum 1500 کی دہائی سے انڈسٹری کا معیاری ڈمی ٹیکسٹ رہا ہے، جب ایک نامعلوم پرنٹر نے قسم کی ایک گیلی لی اور اسے ایک قسم کے نمونے کی کتاب بنانے کے لیے گھسایا۔ یہ نہ صرف پانچ صدیوں تک زندہ رہا ہے بلکہ الیکٹرانک ٹائپ سیٹنگ میں بھی چھلانگ لگا ہوا ہے، بنیادی طور پر کوئی تبدیلی نہیں کی گئی۔ اسے 1960 کی دہائی میں Lorem Ipsum حصئوں پر مشتمل Letraset شیٹس کے اجراء کے ساتھ اور حال ہی میں Aldus PageMaker جیسے ڈیسک ٹاپ پبلشنگ سوفٹ ویئر کے ساتھ مقبول کیا گیا جس میں Lorem Ipsum کے ورژن بھی شامل ہیں۔ ',
                    'status' => 1,
                ),
                array(
                    'name_english' => 'Muhammad Talha',
                    'name_urdu' => 'محمد طلحہ',
                    'name_arabic' => 'محمد طلحہ',
                    'image' => 'images/testimonials-images/dontaner-4.png',
                    'message_english' => "<p>Nam fermentum, ipsum in suscipit pharetra, mi odio aliquet neque, non iaculis augue elipsum in suscipit pharetra, mi odio aliquet neque, non iaculis augue elit it et libero. Phasellus tempor faucibus faucibus. Sed eu mauris sem. Etiam et varius felis. Maecenas interdum lorem eleifend orci aliquam mollis. Aliquam non rhoncus magna</p>",
                    'message_urdu' => 'Lorem Ipsum پرنٹنگ اور ٹائپ سیٹنگ انڈسٹری کا محض ڈمی ٹیکسٹ ہے۔ Lorem Ipsum 1500 کی دہائی سے انڈسٹری کا معیاری ڈمی ٹیکسٹ رہا ہے، جب ایک نامعلوم پرنٹر نے قسم کی ایک گیلی لی اور اسے ایک قسم کے نمونے کی کتاب بنانے کے لیے گھسایا۔ یہ نہ صرف پانچ صدیوں تک زندہ رہا ہے بلکہ الیکٹرانک ٹائپ سیٹنگ میں بھی چھلانگ لگا ہوا ہے، بنیادی طور پر کوئی تبدیلی نہیں کی گئی۔ اسے 1960 کی دہائی میں Lorem Ipsum حصئوں پر مشتمل Letraset شیٹس کے اجراء کے ساتھ اور حال ہی میں Aldus PageMaker جیسے ڈیسک ٹاپ پبلشنگ سوفٹ ویئر کے ساتھ مقبول کیا گیا جس میں Lorem Ipsum کے ورژن بھی شامل ہیں۔ ',
                    'message_arabic' => 'Lorem Ipsum پرنٹنگ اور ٹائپ سیٹنگ انڈسٹری کا محض ڈمی ٹیکسٹ ہے۔ Lorem Ipsum 1500 کی دہائی سے انڈسٹری کا معیاری ڈمی ٹیکسٹ رہا ہے، جب ایک نامعلوم پرنٹر نے قسم کی ایک گیلی لی اور اسے ایک قسم کے نمونے کی کتاب بنانے کے لیے گھسایا۔ یہ نہ صرف پانچ صدیوں تک زندہ رہا ہے بلکہ الیکٹرانک ٹائپ سیٹنگ میں بھی چھلانگ لگا ہوا ہے، بنیادی طور پر کوئی تبدیلی نہیں کی گئی۔ اسے 1960 کی دہائی میں Lorem Ipsum حصئوں پر مشتمل Letraset شیٹس کے اجراء کے ساتھ اور حال ہی میں Aldus PageMaker جیسے ڈیسک ٹاپ پبلشنگ سوفٹ ویئر کے ساتھ مقبول کیا گیا جس میں Lorem Ipsum کے ورژن بھی شامل ہیں۔ ',
                    'status' => 1,
                ),
            )
        );
    }
}
