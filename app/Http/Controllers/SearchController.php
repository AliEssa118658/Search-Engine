<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;

class SearchController extends Controller
{

    protected function buildIndex(ICollection $collection)
    {
        foreach($collection as $id => $document){
            $freqDist = freq_dist($document->getDocumentData());
            foreach($freqDist->getKeyValuesByFrequency() as $key => $freq) {
                if(!isset($this->idf[$key])) {
                    $this->idf[$key] = 0;
                }
                $this->idf[$key]++;
            }
        }

        $count = count($collection);
        foreach($this->idf as $key => &$value) {
            $value = log(($count)/($value));
        }
    }
    public function getIdf($token = null)
    {
        if(!$token){
            return $this->idf;
        }
        return $this->idf[$token];
    }
    public function getTermFrequency(DocumentAbstract $document, $token, $mode = 1)
    {
        $freqDist = new FreqDist($document->getDocumentData());
        $keyValuesByWeight = $freqDist->getKeyValuesByFrequency();

        //The token does not exist in the document
        if(!isset($keyValuesByWeight[$token])) {
            return 0;
        }


    }
    public function search(Request $request)
    {
        $searchQuery = $request->input('content');
        $selectedModel = $request->input('model');

        $documents = $this->getSearchResults($searchQuery, $selectedModel);

        return view('search', compact('documents', 'searchQuery'));
    }


private function getSearchResults($searchQuery, $selectedModel)
{
    $query = Document::query();

    $query->where('content', 'like', '%' . $searchQuery . '%');

    switch ($selectedModel) {
        case 'Boolean':
            // Boolean Model: Basic OR search for any term
            $terms = explode(' ', $searchQuery);
            foreach ($terms as $term) {
                $query->orWhere('content', 'like', '%' . $term . '%');
            }
            break;

        case 'Vector':
            // Vector Model: Basic AND search for all terms
            $terms = explode(' ', $searchQuery);
            foreach ($terms as $term) {
                $query->where('content', 'like', '%' . $term . '%');
            }
            break;
        case 'Extended':
            // Extended Boolean Model: Use OR, AND, NOT operators
            // Example: "word1 OR (word2 AND NOT word3)"
            // Note: This is a simplified example, and you might need a more sophisticated parser.
            $terms = explode(' ', $searchQuery);
            $subQuery = [];
            foreach ($terms as $term) {
                if ($term === 'OR' || $term === 'AND' || $term === 'NOT') {
                    $subQuery[] = $term;
                } else {
                    $subQuery[] = '(content like "%' . $term . '%")';
                }
            }
            $query->whereRaw(implode(' ', $subQuery));
            break;

        // Add more cases if needed

        default:
            // Default case (Boolean model)
            $terms = explode(' ', $searchQuery);
            foreach ($terms as $term) {
                $query->orWhere('content', 'like', '%' . $term . '%');
            }
            break;
    }

    return $query->get();
}

public function index()
    {
        return view('upload');
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:doc,docx|max:10240', // Adjust the file types and size limit as needed
        ]);

        $path = $request->file('file')->store('public');

        Documents::create([
            'path' => $path,
            'content' => $this->extractTextFromWord($path),
        ]);
        return redirect()->route('upload.index')->with('success', 'Document uploaded successfully.');
    }

    private function extractTextFromWord($path)
    {

        if (!Storage::exists($path)) {
            return 'Error: File not found';
        }
        else{
        $file = storage_path('app/' . $path);

        // Load the Word document using IOFactory::load

        $phpWord = IOFactory::load($file);

        // Create a PhpWordReader instance and read the document content

        $phpWordReader = new Word2007();

        $content = $phpWordReader->load($file);

        // Extract text from the content

        $text = '';
        foreach ($content->getSections() as $section) {
            foreach ($section->getElements() as $element) {
                       $text .= $element->getText();
                    }
        }

        return $text;
    }
    }


}
