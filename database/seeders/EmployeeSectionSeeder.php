<?php

namespace Database\Seeders;

use App\Models\Admin\EmployeeSection;
use File;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class EmployeeSectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        EmployeeSection::insert(
            array(
                array(
                    'section_id' => 1,
                    'image' => 'images/ourteam/abid.png',
                    'name_english' => 'Abid Qadrin',
                    'name_urdu' => ' عابد قادرین ',
                    'name_arabic' => ' عابد قدرين',
                    'designation_english' => ' Vice President',
                    'designation_urdu' => ' نائب صدر',
                    'designation_arabic' => ' نائب الرئيس  ',
                    'short_description_english' => "A short description is the initial text for a topic",
                    "short_description_urdu" => "ایک مختصر تفصیل کسی موضوع کا ابتدائی متن ہے۔",
                    "short_description_arabic" => "الوصف المختصر هو النص الأولي للموضوع",
                    'status' => 1,
                ),
                array(
                    'section_id' => 1,
                    'image' => 'images/ourteam/qasim.png',
                    'name_english' => 'Hafiz Muhammad Qasim',
                    'name_urdu' => ' حافظ محمد قاسم ',
                    'name_arabic' => ' حافظ محمد قاسم',
                    'designation_english' => ' secretary General',
                    'designation_urdu' => '   سیکرٹری جنرل',
                    'designation_arabic' => '   الامين العام',
                    'short_description_english' => "A short description is the initial text for a topic",
                    "short_description_urdu" => "ایک مختصر تفصیل کسی موضوع کا ابتدائی متن ہے۔",
                    "short_description_arabic" => "الوصف المختصر هو النص الأولي للموضوع",
                    'status' => 1,
                ),
                array(
                    'section_id' => 1,
                    'image' => 'images/ourteam/ameer-ceo.png',
                    'name_english' => 'Ameer',
                    'name_urdu' => ' أمير ',
                    'name_arabic' => ' أمير',
                    'designation_english' => ' CEO',
                    'designation_urdu' => ' سی ای او',
                    'designation_arabic' => '   المدير التنفيذي ',
                    'short_description_english' => "A short description is the initial text for a topic",
                    "short_description_urdu" => "ایک مختصر تفصیل کسی موضوع کا ابتدائی متن ہے۔",
                    "short_description_arabic" => "الوصف المختصر هو النص الأولي للموضوع",
                    'status' => 1,
                ),
                array(
                    'section_id' => 1,
                    'image' => 'images/ourteam/zahid.png',
                    'name_english' => 'Zahid Mehmood Khan',
                    'name_urdu' => '  زاهد محمود خان ',
                    'name_arabic' => '  زاهد محمود خان',
                    'designation_english' => ' Vice President',
                    'designation_urdu' => ' نائب صدر',
                    'designation_arabic' => ' نائب الرئيس  ',
                    'short_description_english' => "A short description is the initial text for a topic",
                    "short_description_urdu" => "ایک مختصر تفصیل کسی موضوع کا ابتدائی متن ہے۔",
                    "short_description_arabic" => "الوصف المختصر هو النص الأولي للموضوع",
                    'status' => 1,
                ),
                array(
                    'section_id' => 1,
                    'image' => 'images/ourteam/abid.png',
                    'name_english' => 'Abid Qadrin',
                    'name_urdu' => ' عابد قادرین ',
                    'name_arabic' => ' عابد قدرين',
                    'designation_english' => ' Vice President',
                    'designation_urdu' => ' نائب صدر',
                    'designation_arabic' => ' نائب الرئيس  ',
                    'short_description_english' => "A short description is the initial text for a topic",
                    "short_description_urdu" => "ایک مختصر تفصیل کسی موضوع کا ابتدائی متن ہے۔",
                    "short_description_arabic" => "الوصف المختصر هو النص الأولي للموضوع",
                    'status' => 1,
                ),
                array(
                    'section_id' => 2,
                    'image' => 'images/ourteam/abid.png',
                    'name_english' => 'Abid Qadrin',
                    'name_urdu' => ' عابد قادرین ',
                    'name_arabic' => ' عابد قدرين',
                    'designation_english' => ' Vice President',
                    'designation_urdu' => ' نائب صدر',
                    'designation_arabic' => ' نائب الرئيس  ',
                    'short_description_english' => "A short description is the initial text for a topic",
                    "short_description_urdu" => "ایک مختصر تفصیل کسی موضوع کا ابتدائی متن ہے۔",
                    "short_description_arabic" => "الوصف المختصر هو النص الأولي للموضوع",
                    'status' => 1,
                ),
                array(
                    'section_id' => 2,
                    'image' => 'images/ourteam/qasim.png',
                    'name_english' => 'Hafiz Muhammad Qasim',
                    'name_urdu' => ' حافظ محمد قاسم ',
                    'name_arabic' => ' حافظ محمد قاسم',
                    'designation_english' => ' secretary General',
                    'designation_urdu' => '   سیکرٹری جنرل',
                    'designation_arabic' => '   الامين العام',
                    'short_description_english' => "A short description is the initial text for a topic",
                    "short_description_urdu" => "ایک مختصر تفصیل کسی موضوع کا ابتدائی متن ہے۔",
                    "short_description_arabic" => "الوصف المختصر هو النص الأولي للموضوع",
                    'status' => 1,
                ),
                array(
                    'section_id' => 2,
                    'image' => 'images/ourteam/ameer-ceo.png',
                    'name_english' => 'Ameer',
                    'name_urdu' => ' أمير ',
                    'name_arabic' => ' أمير',
                    'designation_english' => ' CEO',
                    'designation_urdu' => ' سی ای او',
                    'designation_arabic' => '   المدير التنفيذي ',
                    'short_description_english' => "A short description is the initial text for a topic",
                    "short_description_urdu" => "ایک مختصر تفصیل کسی موضوع کا ابتدائی متن ہے۔",
                    "short_description_arabic" => "الوصف المختصر هو النص الأولي للموضوع",
                    'status' => 1,
                ),
                array(
                    'section_id' => 2,
                    'image' => 'images/ourteam/zahid.png',
                    'name_english' => 'Zahid Mehmood Khan',
                    'name_urdu' => '  زاهد محمود خان ',
                    'name_arabic' => '  زاهد محمود خان',
                    'designation_english' => ' Vice President',
                    'designation_urdu' => ' نائب صدر',
                    'designation_arabic' => ' نائب الرئيس  ',
                    'short_description_english' => "A short description is the initial text for a topic",
                    "short_description_urdu" => "ایک مختصر تفصیل کسی موضوع کا ابتدائی متن ہے۔",
                    "short_description_arabic" => "الوصف المختصر هو النص الأولي للموضوع",
                    'status' => 1,
                ),
                array(
                    'section_id' => 2,
                    'image' => 'images/ourteam/abid.png',
                    'name_english' => 'Abid Qadrin',
                    'name_urdu' => ' عابد قادرین ',
                    'name_arabic' => ' عابد قدرين',
                    'designation_english' => ' Vice President',
                    'designation_urdu' => ' نائب صدر',
                    'designation_arabic' => ' نائب الرئيس  ',
                    'short_description_english' => "A short description is the initial text for a topic",
                    "short_description_urdu" => "ایک مختصر تفصیل کسی موضوع کا ابتدائی متن ہے۔",
                    "short_description_arabic" => "الوصف المختصر هو النص الأولي للموضوع",
                    'status' => 1,
                ),
                array(
                    'section_id' => 3,
                    'image' => 'images/ourteam/abid.png',
                    'name_english' => 'Abid Qadrin',
                    'name_urdu' => ' عابد قادرین ',
                    'name_arabic' => ' عابد قدرين',
                    'designation_english' => ' Vice President',
                    'designation_urdu' => ' نائب صدر',
                    'designation_arabic' => ' نائب الرئيس  ',
                    'short_description_english' => "A short description is the initial text for a topic",
                    "short_description_urdu" => "ایک مختصر تفصیل کسی موضوع کا ابتدائی متن ہے۔",
                    "short_description_arabic" => "الوصف المختصر هو النص الأولي للموضوع",
                    'status' => 1,
                ),
                array(
                    'section_id' => 3,
                    'image' => 'images/ourteam/qasim.png',
                    'name_english' => 'Hafiz Muhammad Qasim',
                    'name_urdu' => ' حافظ محمد قاسم ',
                    'name_arabic' => ' حافظ محمد قاسم',
                    'designation_english' => ' secretary General',
                    'designation_urdu' => '   سیکرٹری جنرل',
                    'designation_arabic' => '   الامين العام',
                    'short_description_english' => "A short description is the initial text for a topic",
                    "short_description_urdu" => "ایک مختصر تفصیل کسی موضوع کا ابتدائی متن ہے۔",
                    "short_description_arabic" => "الوصف المختصر هو النص الأولي للموضوع",
                    'status' => 1,
                ),
                array(
                    'section_id' => 3,
                    'image' => 'images/ourteam/ameer-ceo.png',
                    'name_english' => 'Ameer',
                    'name_urdu' => ' أمير ',
                    'name_arabic' => ' أمير',
                    'designation_english' => ' CEO',
                    'designation_urdu' => ' سی ای او',
                    'designation_arabic' => '   المدير التنفيذي ',
                    'short_description_english' => "A short description is the initial text for a topic",
                    "short_description_urdu" => "ایک مختصر تفصیل کسی موضوع کا ابتدائی متن ہے۔",
                    "short_description_arabic" => "الوصف المختصر هو النص الأولي للموضوع",
                    'status' => 1,
                ),
                array(
                    'section_id' => 3,
                    'image' => 'images/ourteam/zahid.png',
                    'name_english' => 'Zahid Mehmood Khan',
                    'name_urdu' => '  زاهد محمود خان ',
                    'name_arabic' => '  زاهد محمود خان',
                    'designation_english' => ' Vice President',
                    'designation_urdu' => ' نائب صدر',
                    'designation_arabic' => ' نائب الرئيس  ',
                    'short_description_english' => "A short description is the initial text for a topic",
                    "short_description_urdu" => "ایک مختصر تفصیل کسی موضوع کا ابتدائی متن ہے۔",
                    "short_description_arabic" => "الوصف المختصر هو النص الأولي للموضوع",
                    'status' => 1,
                ),
                array(
                    'section_id' => 3,
                    'image' => 'images/ourteam/abid.png',
                    'name_english' => 'Abid Qadrin',
                    'name_urdu' => ' عابد قادرین ',
                    'name_arabic' => ' عابد قدرين',
                    'designation_english' => ' Vice President',
                    'designation_urdu' => ' نائب صدر',
                    'designation_arabic' => ' نائب الرئيس  ',
                    'short_description_english' => "A short description is the initial text for a topic",
                    "short_description_urdu" => "ایک مختصر تفصیل کسی موضوع کا ابتدائی متن ہے۔",
                    "short_description_arabic" => "الوصف المختصر هو النص الأولي للموضوع",
                    'status' => 1,
                ),
            )
        );
    }
}
