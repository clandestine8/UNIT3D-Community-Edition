<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D Community Edition is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D Community Edition
 *
 * @author     Roardom <roardom@protonmail.com>
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 */

namespace App\Http\Livewire;

use App\Models\Forum;
use App\Models\ForumCategory;
use App\Models\Topic;
use Livewire\Component;
use Livewire\WithPagination;

class ForumCategoryTopicSearch extends Component
{
    use WithPagination;

    public string $search = '';
    public string $sortField = 'last_post_created_at';
    public string $sortDirection = 'desc';
    public string $label = '';
    public string $state = '';
    public string $subscribed = '';
    public string $read = '';
    public ForumCategory $category;

    /**
     * @var array<mixed>
     */
    protected $queryString = [
        'search'        => ['except' => ''],
        'sortField'     => ['except' => 'last_post_created_at'],
        'sortDirection' => ['except' => 'desc'],
        'read'          => ['except' => ''],
        'label'         => ['except' => ''],
        'state'         => ['except' => ''],
        'subscribed'    => ['except' => ''],
    ];

    final public function mount(ForumCategory $category): void
    {
        $this->category = $category;
    }

    final public function updatedPage(): void
    {
        $this->emit('paginationChanged');
    }

    final public function updatingSearch(): void
    {
        $this->resetPage();
    }

    /**
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator<Topic>
     */
    final public function getTopicsProperty(): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return Topic::query()
            ->select('topics.*')
            ->with([
                'user.group',
                'latestPoster',
                'forum',
                'reads' => fn ($query) => $query->whereBelongsto(auth()->user()),
            ])
            ->whereIn('forum_id', Forum::where('forum_category_id', '=', $this->category->id)->select('id'))
            ->authorized(canReadTopic: true)
            ->when($this->search !== '', fn ($query) => $query->where('name', 'LIKE', '%'.$this->search.'%'))
            ->when($this->label !== '', fn ($query) => $query->where($this->label, '=', 1))
            ->when($this->state !== '', fn ($query) => $query->where('state', '=', $this->state))
            ->when(
                $this->subscribed === 'include',
                fn ($query) => $query
                    ->whereRelation('subscribedUsers', 'users.id', '=', auth()->id())
            )
            ->when(
                $this->subscribed === 'exclude',
                fn ($query) => $query
                    ->whereDoesntHave('subscribedUsers', fn ($query) => $query->where('users.id', '=', auth()->id()))
            )
            ->when(
                $this->read === 'some',
                fn ($query) => $query
                    ->whereHas(
                        'reads',
                        fn ($query) => $query
                            ->whereBelongsto(auth()->user())
                            ->whereColumn('last_post_id', '>', 'last_read_post_id')
                    )
            )
            ->when(
                $this->read === 'all',
                fn ($query) => $query
                    ->whereHas(
                        'reads',
                        fn ($query) => $query
                            ->whereBelongsto(auth()->user())
                            ->whereColumn('last_post_id', '=', 'last_read_post_id')
                    )
            )
            ->when(
                $this->read === 'none',
                fn ($query) => $query
                    ->whereDoesntHave('reads', fn ($query) => $query->whereBelongsTo(auth()->user()))
            )
            ->orderByDesc('pinned')
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(25);
    }

    final public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        return view('livewire.forum-category-topic-search', [
            'topics' => $this->topics,
        ]);
    }
}
