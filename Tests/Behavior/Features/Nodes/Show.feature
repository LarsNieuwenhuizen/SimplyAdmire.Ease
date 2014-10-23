Feature:

  Background:
    Given I have the following nodes:
      | Identifier                           | Path        | Node Type              | Properties        | Workspace |
      | ecf40ad1-3119-0a43-d02e-55f8b5aa3c70 | /sites      | unstructured           |                   | live      |
      | fd5ba6e1-4313-b145-1004-dad2f1173a35 | /sites/ease | SimplyAdmire.Ease:Wall | {"title": "Home"} | live      |

  @fixtures
  Scenario:
    Given I accept "application/json"
    And I send a "GET" request to "ease/nodes/ecf40ad1-3119-0a43-d02e-55f8b5aa3c70"
    Then the response status code should be 200
    And the response is JSON
    And the response should contain 1 node
    And the node should have property "@path" with value "/sites"
    And the node should have identifier "ecf40ad1-3119-0a43-d02e-55f8b5aa3c70"

  @fixtures
  Scenario:
    Given I accept "application/json"
    And I send a "GET" request to "ease/nodes/fd5ba6e1-4313-b145-1004-dad2f1173a35"
    Then the response status code should be 200
    And the response is JSON
    And the response should contain 1 node
    And the node should have property "@path" with value "/sites/ease"
    And the node should have identifier "fd5ba6e1-4313-b145-1004-dad2f1173a35"

  @fixtures
  Scenario:
    Given I accept "text/html"
    And I send a "GET" request to "ease/nodes/ecf40ad1-3119-0a43-d02e-55f8b5aa3c70"
    Then the response status code should be 200
    And the response is HTML
    And the response should contain 1 node
    And the node should have property "@path" with value "/sites"
    And the node should have identifier "ecf40ad1-3119-0a43-d02e-55f8b5aa3c70"

  @fixtures
  Scenario:
    Given I accept "text/html"
    And I send a "GET" request to "ease/nodes/fd5ba6e1-4313-b145-1004-dad2f1173a35"
    Then the response status code should be 200
    And the response is HTML
    And the response should contain 1 node
    And the node should have property "@path" with value "/sites/ease"
    And the node should have identifier "fd5ba6e1-4313-b145-1004-dad2f1173a35"