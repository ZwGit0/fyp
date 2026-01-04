<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductType;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Tables;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $slug = 'products';

    protected static ?string $label = 'Product';
    protected static ?string $pluralLabel = 'Products';

    // Create the form fields for the product
    public static function form(Form $form): Form
    {
        return $form->schema([
            // Product name
            Forms\Components\TextInput::make('name')
                ->required()
                ->maxLength(255),

            // Product price
            Forms\Components\TextInput::make('price')
                ->required()
                ->numeric()
                ->prefix('RM '),

            // Product image
            Forms\Components\FileUpload::make('image')
                ->label('Product Image')
                ->image() // This ensures only image files are uploaded
                ->required()
                ->preserveFilenames() // Keeps original file name
                ->maxSize(1024) // Optional: limit the file size (in KB)
                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/gif']) // Optional: restrict file types to image formats
                ->directory(fn (callable $get) => 
                    'images/' . self::getFolderName($get('categories')[0] ?? null) // Get first category ID
                ),
            
            // Main Category selection (Dropdown)
            Forms\Components\Select::make('product_type_id')
            ->label('Product')
            ->options(ProductType::pluck('name', 'id')) // Fetch from DB
            ->required()
            ->reactive() // Make it reactive so that subcategories update when the main category is selected
            ->afterStateUpdated(function (callable $set, $state) {
                // Ensure product type is selected before resetting categories
                if ($state) {
                    $set('categories', []);
                    $set('attributes', []);
                }
            }),

            // Sub Category selection (Dropdown)
            Forms\Components\MultiSelect::make('categories')
            ->label('Categories')
            ->relationship('categories', 'name') // Keep the many-to-many relationship
            ->options(fn (callable $get) => 
                $get('product_type_id') 
                    ? Category::where('product_type_id', $get('product_type_id'))->pluck('name', 'id')->toArray() 
                    : []
            ) 
            ->reactive() // Ensures update when `product_id` changes
            ->preload(true) // Prevents loading all categories initially
            ->searchable() // Ensures search works within the filtered options
            ->required()
            ->afterStateHydrated(function ($set, $state, callable $get) {
                if ($get('product_type_id')) {
                    $filteredCategories = Category::where('product_type_id', $get('product_type_id'))->pluck('id')->toArray();
                    $set('categories', array_intersect($state, $filteredCategories));
                }
            }),

            // Stock
            Forms\Components\TextInput::make('stock')
                ->numeric()
                ->default(0)
                ->required(),

            // Description
            Forms\Components\Textarea::make('description')
                ->nullable(),

            // Dynamic attributes (JSON column)
            Forms\Components\KeyValue::make('attributes')
                ->keyLabel('Attribute Name')
                ->valueLabel('Attribute Value')
                ->label('Product Attributes')
                ->helperText('Enter specific attributes for this product.')
                ->reactive()
                ->visible(fn ($get) => !empty($get('categories')))
                ->afterStateUpdated(function ($set, callable $get, $state) {
                    $categories = $get('categories'); // Get selected categories
                
                    if (!empty($categories)) {

                        $newAttributes = [];

                        // **Clothing: Shirts & Pants**
                        if (array_intersect($categories, [1, 2, 3, 4, 5, 6, 7, 8])) {
                            $newAttributes = [
                                "Washing recommendation" => '',
                                "Fabric type" => '', // Type of fabric (cotton, polyester, etc.)
                                "Size" => '',
                            ];
                        } 
                        // **Shoes**
                        elseif (array_intersect($categories, [9, 10, 11, 12])) {
                            $newAttributes = [
                                "Shoe material" => '', // Leather, mesh, etc.
                                "Sole type" => '', // Rubber, foam, etc.
                                "Waterproof" => '', // Yes/No
                                "Size (US)" => '',
                            ];
                        }
                        // **Phones**
                        elseif (array_intersect($categories, [13])) {
                            $newAttributes = [
                                "Processor" => '',
                                "Ram" => '',
                                "Storage" => '',
                                "Battery" => '',
                                "Camera" => '',
                                "Screen size" => '',
                            ];
                        }
                        // **Vacuum Cleaners**
                        elseif (array_intersect($categories, [14, 15])) {
                            $newAttributes = [
                                "Suction power" => '', // Power in Watts (W)
                                "Dust capacity" => '', // Capacity in Liters (L)
                                "Battery life" => '', // For cordless vacuums
                                "Filter type" => '', // HEPA, foam, etc.
                            ];
                        }
                        // **TVs**
                        elseif (array_intersect($categories, [16, 17])) {
                            $newAttributes = [
                                "Screen size" => '', // 32", 55", etc.
                                "Resolution" => '', // 4K, 8K, 1080p
                                "Panel type" => '', // OLED, QLED, LED
                                "Refresh rate" => '', // 60Hz, 120Hz, etc.
                                "Smart TV" => '', // Yes/No
                                "HDMI ports" => '', // Number of HDMI ports
                            ];
                        } 

                        // Preserve manually entered values
                        $set('attributes', array_merge($newAttributes, $state ?? []));
                    } 
                })
        ]);
    }

    protected static function getFolderName($categoryId): string
    {
        $folderMap = [
            1 => 'Men shirts',
            2 => 'Women shirts',
            3 => 'Men shirts',
            4 => 'Women shirts',

            5 => 'Men pants',
            6 => 'Women pants',
            7 => 'Men pants',
            8 => 'Women pants',

            9 => 'Men shoes',
            10 => 'Women shoes',
            11 => 'Men shoes',
            12 => 'Women shoes',

            13 => 'Phone',

            14 => 'Vacuum',
            15 => 'Vacuum',

            16 => 'TV',
            17 => 'TV',
        ];

        // Return the folder name if found, otherwise use 'other' as default
        return $folderMap[$categoryId] ?? 'other';
    }


    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('name')->searchable(),
            Tables\Columns\TextColumn::make('price'),
            Tables\Columns\TextColumn::make('categories.name')->label('Categories') // This will display categories
                ->getStateUsing(function ($record) {
                    return $record->categories->pluck('name')->implode(', '); // Show category names
                }),

            // Seller ID Column
            Tables\Columns\TextColumn::make('seller_id')->label('Seller ID')
                ->getStateUsing(function ($record) {
                    return $record->seller ? $record->seller->id : 'N/A';
                }),
                
            // Seller Name Column
            Tables\Columns\TextColumn::make('seller.name')->label('Seller Name')
            ->getStateUsing(function ($record) {
                return $record->seller ? $record->seller->name : 'Admin';
            }),
        ]);
    }

    
    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }    

    public static function afterSave($record, array $data)
    {
        if (isset($data['categories'])) {
            $record->categories()->sync($data['categories']);
        }
    }

}
