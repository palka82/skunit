<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        DB::table('groups')->insert([
            'name' => 'Администраторы',
            'description' => 'Группа Администраторы'
        ]);
        
        DB::table('groups')->insert([
            'name' => 'Пользователи',
            'description' => 'Группа Пользователи'
        ]);

        DB::table('speech_modules')->insert([
            'name' => 'Речь для входщих взонков',
            'speech' => 'Всем привет.'
        ]);

        DB::table('forms')->insert([
            'name' => 'form_in',
            'description' => 'Форма для входящих звонков',
            'speech_id' => 1
        ]);

        DB::table('form_elements')->insert([
            'type' => 'input_text',
            'name' => 'fio',
            'label' => 'ФИО пациента',
            'form_id' => 1,
            'attr' => 'readonly  class="form-control-plaintext"',
        ]);

        DB::table('form_elements')->insert([
            'type' => 'input_text',
            'name' => 'fio2',
            'form_id' => 1,
            'attr' => 'class="form-control-plaintext"',
        ]);

        DB::table('clinics')->insert([
           'name' => 'премиум'
        ]);

        DB::table('clinics')->insert([
            'name' => 'бэби'
        ]);

        DB::table('clinics')->insert([
            'name' => 'комфорт'
        ]);

        DB::table('clinics')->insert([
            'name' => 'янг'
        ]);

        DB::table('clinics')->insert([
            'name' => 'call-центр'
        ]);

        DB::table('form_elements')->insert([
            'type' => 'select',
            'name' => 'clinics',
            'value' => 'tbl.clinics',
            'label' => 'Клиника',
            'attr' => 'onChange="{if(this.value == 2){ $(\'#grp_fio2\').show() } else { $(\'#grp_fio2\').hide() };}" class="form-control',
            'form_id' => 1
        ]);

        DB::table('form_elements')->insert([
            'type' => 'time',
            'name' => 'visit_time',
            'label' => 'Время приема',
            'attr' => 'class="form-control',
            'form_id' => 1
        ]);

        DB::table('form_elements')->insert([
            'type' => 'date',
            'name' => 'visit_date',
            'label' => 'Дата приема',
            'attr' => 'class="form-control',
            'form_id' => 1
        ]);
    }
}
