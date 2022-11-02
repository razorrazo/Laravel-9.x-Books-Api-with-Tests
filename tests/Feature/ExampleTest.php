<?php
use App\Models\Book;
use Tests\Cases;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BooksApiTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    function can_get_all_books(){
      
        $books = Book::factory(4)->create();

        $response= $this->getJson(route('books.index'));
        
        $response->assertJsonFragment([
            'title'=>$books[0]->title,  
        ])->assertJsonFragment([
            'title'=>$books[1]->title
        ]);

    }

    /** @test */
    function can_get_one_books(){
      
        $book = Book::factory()->create();

        $this->getJson(route('books.show',$book))
            ->assertJsonFragment([
                'title'=>$book->title
            ]);

    }
    
    /** @test */
    function can_create_all_books(){
      
        $book = Book::factory()->create();

        $this->postJson(route('books.store'),[])
            ->assertJsonValidationErrorFor('title');

        $this->postJson(route('books.store'),[
        ])->assertJsonFragment([
            'title'=>'My new book'
        ]);

        $this->assertDataHas('books.store',[
            'title'=>'My new book'
        ]);
    }

    /** @test */
    function can_update_all_books(){
      
        $book = Book::factory()->create();

        $this->patchJson(route('books.update',$book),[
            'title'=>$book->title
        ])->assertJsonFragment([
            'title'=>$book->title  
        ]);

    }

    /** @test */
    function can_delete_all_books(){
      
        $book = Book::factory()->create();

        $this->getJson(route('books.destroy',$book))
            ->assertJsonFragment();

        $this->assertDatabaseCount('books',0);

    }
}
