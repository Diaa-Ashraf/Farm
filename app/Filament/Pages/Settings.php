<?php

namespace App\Filament\Pages;

use App\Models\User;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Hash;
use UnitEnum;

class Settings extends Page
{
    protected static ?string $title = 'إعدادات الحساب';
    protected static ?string $navigationLabel = 'إعدادات الحساب';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::Cog6Tooth;
    protected static string|UnitEnum|null $navigationGroup = 'الإعدادات';

    public ?string $name = '';
    public ?string $email = '';
    public ?string $profile_picture = '';
    public ?string $logo = '';
    public ?string $current_password = '';
    public ?string $password = '';
    public ?string $password_confirmation = '';

    public function mount(): void
    {
        $user = auth()->user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->profile_picture = $user->profile_picture;
        $this->logo = $user->logo;
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('البيانات الشخصية')
                    ->description('معلومات حسابك الأساسية')
                    ->schema([
                        TextInput::make('name')
                            ->label('الاسم')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('email')
                            ->label('البريد الإلكتروني')
                            ->email()
                            ->required()
                            ->maxLength(255),
                    ]),




                Section::make('تغيير كلمة السر')
                    ->description('قم بتحديث كلمة السر الخاصة بك')
                    ->schema([
                        TextInput::make('current_password')
                            ->label('كلمة السر الحالية')
                            ->password()
                            ->required(fn() => !empty($this->password)),

                        TextInput::make('password')
                            ->label('كلمة السر الجديدة')
                            ->password()
                            ->minLength(8),

                        TextInput::make('password_confirmation')
                            ->label('تأكيد كلمة السر')
                            ->password()
                            ->required(fn() => !empty($this->password)),
                    ])
                    ->collapsible(),
            ]);
    }

    protected function getActions(): array
    {
        return [
            Action::make('save')
                ->label('حفظ التغييرات')
                ->action('save'),
        ];
    }

    public function save(): void
    {
        $user = auth()->user();

        // Validate email uniqueness
        if ($this->email !== $user->email) {
            if (User::where('email', $this->email)->exists()) {
                Notification::make()
                    ->title('البريد الإلكتروني مستخدم بالفعل')
                    ->danger()
                    ->send();
                return;
            }
        }

        // Validate password
        if (!empty($this->password)) {
            if (empty($this->current_password)) {
                Notification::make()
                    ->title('يجب إدخال كلمة السر الحالية')
                    ->danger()
                    ->send();
                return;
            }

            if ($this->password !== $this->password_confirmation) {
                Notification::make()
                    ->title('كلمات السر غير متطابقة')
                    ->danger()
                    ->send();
                return;
            }

            if (!Hash::check($this->current_password, $user->password)) {
                Notification::make()
                    ->title('كلمة السر الحالية غير صحيحة')
                    ->danger()
                    ->send();
                return;
            }
        }

        // Update user
        $updateData = [
            'name' => $this->name,
            'email' => $this->email,
            'profile_picture' => $this->profile_picture ?? $user->profile_picture,
            'logo' => $this->logo ?? $user->logo,
        ];

        if (!empty($this->password)) {
            $updateData['password'] = Hash::make($this->password);
        }

        $user->update($updateData);

        // Reset password fields
        $this->current_password = '';
        $this->password = '';
        $this->password_confirmation = '';

        Notification::make()
            ->title('تم حفظ التغييرات بنجاح')
            ->success()
            ->send();
    }
}
