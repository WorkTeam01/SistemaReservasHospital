#!/bin/bash
# Script para sincronizar labels desde labels.yml a GitHub

if [ $# -ne 1 ]; then
    echo "Uso: $0 <GITHUB_TOKEN>"
    exit 1
fi

TOKEN=$1
OWNER="WorkTeam01"
REPO="SistemaReservasHospital"
API_URL="https://api.github.com/repos/$OWNER/$REPO/labels"

echo "🏷️  Sincronizando labels..."

# Función para crear label
create_label() {
    local name="$1"
    local color="$2"
    local description="$3"
    
    response=$(curl -s -X POST \
        -H "Authorization: token $TOKEN" \
        -H "Accept: application/vnd.github.v3+json" \
        "$API_URL" \
        -d "{\"name\":\"$name\",\"color\":\"$color\",\"description\":\"$description\"}")
    
    if echo "$response" | grep -q '"name"'; then
        echo "  ✓ $name"
    fi
    sleep 0.1
}

# Priority Labels
create_label "priority: critical" "d73a4a" "Critical priority - requires immediate attention"
create_label "priority: high" "ff6b6b" "High priority - should be addressed soon"
create_label "priority: medium" "fbca04" "Medium priority - normal workflow"
create_label "priority: low" "0e8a16" "Low priority - can be scheduled later"

# Type Labels
create_label "type: bug" "d73a4a" "Something isn't working correctly"
create_label "type: feature" "a2eeef" "New feature or request"
create_label "type: enhancement" "84b6eb" "Improvement to existing functionality"
create_label "type: documentation" "0075ca" "Documentation improvements or additions"
create_label "type: testing" "bfd4f2" "Related to testing (unit, integration, e2e)"
create_label "type: refactor" "fbca04" "Code refactoring without changing functionality"
create_label "type: security" "ee0701" "Security-related issues or improvements"

# Status Labels
create_label "status: needs-review" "fbca04" "Awaiting review from team members"
create_label "status: in-progress" "1d76db" "Currently being worked on"
create_label "status: blocked" "b60205" "Blocked by dependencies or other issues"
create_label "status: on-hold" "d4c5f9" "Temporarily paused"
create_label "status: ready" "0e8a16" "Ready to be worked on"

# Module Labels
create_label "module: auth" "c5def5" "Authentication and authorization module"
create_label "module: usuarios" "c5def5" "User management module"
create_label "module: pacientes" "c5def5" "Patient management module"
create_label "module: citas" "c5def5" "Appointments management module"
create_label "module: especialidades" "c5def5" "Medical specialties module"
create_label "module: dashboard" "c5def5" "Dashboard and statistics module"
create_label "module: reportes" "c5def5" "Reports and analytics module"
create_label "module: core" "c5def5" "Core system functionality (MVC, Router, etc.)"

# Effort Labels
create_label "effort: small" "c2e0c6" "Small effort - less than 1 day"
create_label "effort: medium" "f9d0c4" "Medium effort - 1-3 days"
create_label "effort: large" "f87171" "Large effort - more than 3 days"

# Special Labels
create_label "good first issue" "7057ff" "Good for newcomers to the project"
create_label "help wanted" "008672" "Extra attention or help needed"
create_label "duplicate" "cfd3d7" "This issue or pull request already exists"
create_label "invalid" "e4e669" "This doesn't seem right or valid"
create_label "wontfix" "ffffff" "This will not be worked on"
create_label "question" "d876e3" "Further information is requested"
create_label "dependencies" "0366d6" "Related to project dependencies"
create_label "breaking change" "b60205" "Introduces breaking changes to the API"

echo ""
echo "✅ Completado!"
echo "Ver: https://github.com/$OWNER/$REPO/labels"
