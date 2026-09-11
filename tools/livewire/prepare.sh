#!/usr/bin/env bash
set -euo pipefail

demo_directory="$(realpath "$(dirname "${BASH_SOURCE[0]}")/../..")"
livewire_directory="$(dirname "$demo_directory")/livewire"
patch_file="$demo_directory/tools/livewire/destroyed-component-responses.patch"
base_commit=ec19e5fb1e0b60df22ad15b901c2f7740267ba11

if [[ ! -e "$livewire_directory" ]]; then
    git clone --depth 1 --branch v3.8.8 https://github.com/livewire/livewire.git "$livewire_directory"
fi

if [[ "$(git -C "$livewire_directory" rev-parse HEAD)" != "$base_commit" ]]; then
    echo "Expected the adjacent Livewire checkout at v3.8.8 ($base_commit)." >&2
    exit 1
fi

if git -C "$livewire_directory" apply --reverse --check "$patch_file" 2>/dev/null; then
    echo 'The current destroyed-component response patch is already applied.'
else
    git -C "$livewire_directory" apply --check "$patch_file"
    git -C "$livewire_directory" apply "$patch_file"
fi

echo 'Livewire source prepared. Build and install its assets as described in tools/livewire/README.md.'
