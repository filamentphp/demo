#!/usr/bin/env bash
set -euo pipefail

demo_directory="$(realpath "$(dirname "${BASH_SOURCE[0]}")/../..")"
inertia_directory="$(dirname "$demo_directory")/inertia"
patch_file="$demo_directory/tools/inertia/external-navigation.patch"
base_commit=721eef6464ac53c84b52da2f2898d048eea1efcb

if [[ ! -e "$inertia_directory" ]]; then
    git clone --depth 1 --branch v3.7.0 https://github.com/inertiajs/inertia.git "$inertia_directory"
fi

if [[ "$(git -C "$inertia_directory" rev-parse HEAD)" != "$base_commit" ]]; then
    echo "Expected the adjacent Inertia checkout at v3.7.0 ($base_commit)." >&2
    exit 1
fi

if git -C "$inertia_directory" apply --reverse --check "$patch_file" 2>/dev/null; then
    echo 'The current external-navigation patch is already applied.'
else
    git -C "$inertia_directory" apply --check "$patch_file"
    git -C "$inertia_directory" apply "$patch_file"
fi

echo 'Inertia source prepared. Install and build the packages before installing demo dependencies.'
