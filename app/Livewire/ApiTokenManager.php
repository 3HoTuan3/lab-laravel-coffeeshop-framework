<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\NewAccessToken;
use Laravel\Sanctum\PersonalAccessToken;
use Livewire\Component;

class ApiTokenManager extends Component
{
    /**
     * List allowed permissions
     *
     * @var string[]
     */
    public array $permissions = [
        'create', 'read', 'update', 'delete',
    ];

    /**
     * List default permissions
     *
     * @var string[]
     */
    public array $defaultPermissions = [
        'read',
    ];

    /**
     * The create API token form state.
     *
     * @var array<string, mixed>
     */
    public array $createApiTokenForm = [
        'name' => '',
        'permissions' => [],
    ];

    /**
     * The plain text token value.
     */
    public ?string $plainTextToken = null;

    /**
     * The token that is currently having its permissions managed.
     */
    public ?PersonalAccessToken $managingPermissionsFor = null;

    /**
     * The update API token form state.
     *
     * @var array<string, mixed>
     */
    public array $updateApiTokenForm = [
        'permissions' => [],
    ];

    /**
     * The ID of the API token being deleted.
     */
    public ?int $apiTokenIdBeingDeleted = null;

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->createApiTokenForm['permissions'] = $this->defaultPermissions;
    }

    /**
     * Return the permissions in the given list that are actually defined permissions for the application.
     */
    private function validPermissions(array $permissions): array
    {
        return array_values(array_intersect($permissions, $this->permissions));
    }

    /**
     * Create a new API token.
     */
    public function createApiToken(): void
    {
        $this->resetErrorBag();

        Validator::make([
            'name' => $this->createApiTokenForm['name'],
        ], [
            'name' => ['required', 'string', 'max:255'],
        ])->validateWithBag('createApiToken');

        $this->displayTokenValue(auth()->user()->createToken(
            $this->createApiTokenForm['name'],
            $this->validPermissions($this->createApiTokenForm['permissions'])
        ));

        $this->createApiTokenForm['name'] = '';
        $this->createApiTokenForm['permissions'] = $this->defaultPermissions;

        $this->dispatch('created');
    }

    /**
     * Display the token value to the user.
     */
    protected function displayTokenValue(NewAccessToken $token): void
    {
        $this->plainTextToken = explode('|', $token->plainTextToken, 2)[1];

        $this->dispatch('open-modal', 'displaying-token');
    }

    /**
     * Allow the given token's permissions to be managed.
     */
    public function manageApiTokenPermissions(int $tokenId): void
    {
        $this->managingPermissionsFor = auth()->user()->tokens()->where('id', $tokenId)->firstOrFail();

        $this->updateApiTokenForm['permissions'] = $this->managingPermissionsFor->abilities;

        $this->dispatch('open-modal', 'managing-api-token-permissions');
    }

    /**
     * Update the API token's permissions.
     */
    public function updateApiToken(): void
    {
        $this->managingPermissionsFor->forceFill([
            'abilities' => $this->validPermissions($this->updateApiTokenForm['permissions']),
        ])->save();

        $this->dispatch('close');
    }

    /**
     * Confirm that the given API token should be deleted.
     */
    public function confirmApiTokenDeletion(int $tokenId): void
    {
        $this->apiTokenIdBeingDeleted = $tokenId;

        $this->dispatch('open-modal', 'confirming-api-token-deletion');
    }

    /**
     * Delete the API token.
     */
    public function deleteApiToken(): void
    {
        auth()->user()->tokens()->where('id', $this->apiTokenIdBeingDeleted)->first()?->delete();

        auth()->user()->load('tokens');

        $this->managingPermissionsFor = null;

        $this->dispatch('close');
    }

    /**
     * Get the current user of the application.
     */
    public function getUserProperty()
    {
        return Auth::user();
    }

    /**
     * Render the component.
     */
    public function render()
    {
        return view('livewire.api-token-manager');
    }
}
